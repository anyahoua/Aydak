<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Models\UserAdresse;
use App\Models\UserInfo;
//use App\Models\UserCommande;
use App\Models\DocUser;
use App\Models\GroupeUser;
use App\Models\Groupe;
use App\Models\InvitationShopper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\UploadTrait;
use Validator;
use Keygen;

use App\Http\Resources\Api\UserLoginRessource;
use App\Http\Resources\Api\UserRessource;
use App\Http\Resources\Api\OrdersRessource;

class UserController extends Controller
{
    use UploadTrait;

    /** 
     * Login API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function login(Request $request)
    {

        $input = $request->all();

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password']))) {

            $user = Auth::user();

            if ($user->userInfo->etat == 1) {
                $token =  $user->createToken('AydakUsers')->accessToken;

                $user->apitoken = $token;
                //$user->userInfo;
                //$user->userInfo->profil;
                $user->userLocationAddress;
                $user->userLocationAddress->pays;

                return response()->json([
                    'code'      => '200',
                    'message'   => 'Authentification réussie.',
                    //'data'      => new UserLoginResource($user)
                    //'apiToken'  => $token,
                    'data'      => new UserLoginRessource($user),

                ], 200);
            } else {
                return response()->json(['error' => 'Unauthorised status'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /** 
     * Invitation Shopper API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function InvitationShopper(Request $request)
    {

        $data = $request->validate(
            [
                'userId'            => 'required| integer ',
                'mobile'            => ['required', 'regex:/^(05|06|07)[0-9]{8}$/'],
            ],
            [
                'userId.required'   => 'Le champ userId est obligatoire.',
                'userId.integer'    => 'userId doite être un entier.',
                'mobile.required'   => 'Le champ mobile est obligatoire.',
                'regex.required'    => 'Le champ mobile est invalide.',
            ]
        );


        // If Mobile exist
        $coursier = User::where('username', $request->mobile)->first();
        if(!empty($coursier)){
            return response()->json(['error' => 'Unauthorised!. This mobile exist.'], 401);
        }


        // Get Teamleader :
        //-----------------------------
        $Teamleader = User::find($request->userId);
        //$Teamleader = User::findOrFail($request->userId);
        if(!empty($Teamleader)){
        
            $Teamleader->groupeUser;
            $Teamleader->userInfo;
            $Teamleader->groupe;
            $Teamleader->invitations;
            $Teamleader->documents;

            if($Teamleader->userInfo->profil->id == '1'){

                //$firstInvitations = InvitationShopper::where('mobile', $request->mobile)->where('etat', '0')->get();
                

                // Generate code : 
                //-----------------------------
                $code = $this->generateCode();
                
                // Disable All Old Invitations Shopper :
                //-----------------------------
                InvitationShopper::where('mobile', $request->mobile)->where('etat', '0')->update(['etat' => '1']);

                // Add Invitation Shopper :
                //-----------------------------
                $Invitation                     = new InvitationShopper;

                $Invitation->mobile             = $request->mobile;
                $Invitation->code               = $code;
                //$Invitation->date_activation    = '';
                $Invitation->etat               = '0';
                $Invitation->user_id            = $Teamleader->id;
                $Invitation->groupe_id          = $Teamleader->groupeUser->groupe_id;

                $Invitation->save();
            } else {
                return response()->json(['error' => 'Unauthorised for this action.'], 401);
            }

        } else {
            return response()->json(['error' => 'User not found.'], 404);
        }

        return response()->json([
            'code'      => '200',
            'message'   => 'Invitation à bien été envoyée.',
            'data'      => $Invitation

        ], 200);
    }

    /** 
     * Validate Code For Shopper API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function validateCode(Request $request)
    {
        $data = $request->validate(
            [
                'code'              => 'required| integer ',
                'mobile'            => ['required', 'regex:/^(05|06|07)[0-9]{8}$/'],
            ],
            [
                'code.required'     => 'Le champ code est obligatoire.',
                'mobile.required'   => 'Le champ mobile est obligatoire.',
                'regex.required'    => 'Le champ mobile est invalide.',
            ]
        );

        $query = InvitationShopper::where('code', $request->code)->where('mobile', $request->mobile)->where('etat', '0');

        $Invitation = $query->first();

        if(!empty($Invitation)){

            $Invitation->user;
            $Invitation->groupe;

            return response()->json([
                'code'      => '200',
                'message'   => 'Le coursier à bien été invité.',
                'data'      => $Invitation

            ], 200);
        }
        
        return response()->json(['error' => 'unauthorized code'], 401);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $request)
    {
        return Validator::make($request, [
            'lastName'      => ['required', 'string', 'max:255'],
            'firstName'     => ['required', 'string', 'max:255'],
            'address'       => ['required', 'string', 'max:255'],
            'profil'        => ['required', 'integer'],
            'username'      => ['required', 'regex:/^(05|06|07)[0-9]{8}$/', 'unique:users'], // Mobile
            //'password'      => ['required', 'string', 'min:8', 'confirmed'], //---> password_confirmation = 'le mot de passe'
            'password'      => ['required', 'string', 'min:8'],
            'c_password'    => ['required', 'same:password'],
        ]);

    }

    /** 
     * Register First Step Teamleader And Shopper API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function register(Request $request)
    {
        // Validate input request.
        $this->validator($request->all())->validate();

        // Input data user :
        $data = [
            'nom'       => $request->lastName,
            'prenom'    => $request->firstName,
            'username'  => $request->username,
            'password'  => bcrypt($request->password)
        ];

        // Add User :
        $user   = User::create($data);
        //--

        $token  =  $user->createToken('AydakUsers')->accessToken;

        $user->apitoken = $token;

        //      config('global.country_id')

        // Add User infos :
        //------------------
        $userInfos                      = new UserInfo;

        $userInfos->mobile              = $request->username;
        $userInfos->adresse_residence   = $request->address;
        $userInfos->user_id             = $user->id;
        $userInfos->profil_id           = $request->profil;
        $userInfos->etat                = '0';
        $userInfos->etape               = '1';

        $userInfos->save();

        // Add User Location Address :
        //----------------------------
        $UserLocationAddress                = new UserAdresse;
        
        $UserLocationAddress->latitude      = $request->latitude;
        $UserLocationAddress->longitude     = $request->longitude;
        $UserLocationAddress->quartier      = $request->district;
        $UserLocationAddress->commune       = $request->commune;
        $UserLocationAddress->daira         = $request->daira;
        $UserLocationAddress->wilaya        = $request->wilaya;
        $UserLocationAddress->pays_id       = config('global.country_id');
        $UserLocationAddress->user_id       = $client->id;
        $UserLocationAddress->etat          = '1';

        $UserLocationAddress->save();


        //
        $user->userInfo;

        return response()->json([
            'code'      => '201',
            'message'   => 'Inscription (etape 1) réussie.',
            'data'      => $user

        ], 201);
    }

    /** 
     * Register Next Step Teamleader API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function registerNextTeamleader(Request $request)
    {
        $data = $request->validate(
            [
                'userId'            => 'required| integer',
                'groupeName'        => 'required| string | unique:groupes,nom',
                'district'          => 'required| string',
                'commune'           => 'required| string',
                'daira'             => 'required| string',
                'wilaya'            => 'required| string',
                'latitude'          => 'required| numeric',
                'longitude'         => 'required| numeric',
                'cardId'            => 'required| image | mimes:jpeg,png,jpg |max:2048',
                'rapSheet'          => 'required| image | mimes:jpeg,png,jpg |max:2048',
            ],
            [
                'userId.required'       => 'Le champ userId est obligatoire.',
                'userId.integer'        => 'userId doite être un entier.',
                'groupeName.required'   => 'Le champ groupeId est obligatoire.',
                'district.required'     => 'Le champ district est obligatoire.',
                'commune'               => 'Le champ commune est obligatoire.',
                'daira'                 => 'Le champ daira est obligatoire.',
                'wilaya'                => 'Le champ wilaya est obligatoire.',
                'latitude'              => 'Le champ latitude est obligatoire.',
                'longitude'             => 'Le champ longitude est obligatoire.',
                'cardId.required'       => 'L\'image cardId est obligatoire.',
                'rapSheet.required'     => 'L\'image rapSheet est obligatoire.',
            ]
        );

        //return Groupe::where('nom', $request->groupeName)->count();

        //-----------------------------------------
        // Add New Groupe
        //-----------------------------------------
        $Groupe                 = new Groupe;

        $Groupe->nom            = $request->groupeName;
        //$Groupe->photo          = '';
        $Groupe->daira          = $request->daira;
        $Groupe->latitude       = $request->latitude;
        $Groupe->longitude      = $request->longitude;
        $Groupe->etat           = '1';

        $Groupe->save();
        //-----------------------------------------



        // Card ID
        if ($request->hasFile('cardId')) {
            $CardID                 = $request->file('cardId');
            //$CardID_Name    = $request->userId.'_'.$this->generateCode().'.'.$CardID->getClientOriginalExtension();
            $CardID_Name            = $this->generateDocName() . '.' . $CardID->getClientOriginalExtension();
            $folder                 = '/users/cardid';  // Define folder path

            // Upload image
            $this->uploadOne($CardID, $folder, 'public', $CardID_Name);

            //-----------------------------------------
            // Add Card ID
            //-----------------------------------------
            $DocCardID              = new DocUser;

            $DocCardID->doc         = $CardID_Name;
            $DocCardID->etat        = '1';
            $DocCardID->user_id     = $request->userId;

            $DocCardID->save();
            //-----------------------------------------
        }

        // Rap Sheet
        if ($request->hasFile('rapSheet')) {
            $RapSheet               = $request->file('rapSheet');
            //$RapSheet_Name  = $request->userId.'_'.$this->generateCode().'.'.$RapSheet->getClientOriginalExtension();
            $RapSheet_Name          = $this->generateDocName() . '.' . $RapSheet->getClientOriginalExtension();
            $folder                 = '/users/rapSheet';  // Define folder path

            // Upload image
            $this->uploadOne($RapSheet, $folder, 'public', $RapSheet_Name);

            //-----------------------------------------
            // Add Rap Sheet
            //-----------------------------------------
            $DocRapSheet            = new DocUser;

            $DocRapSheet->doc       = $RapSheet_Name;
            $DocRapSheet->etat      = '1';
            $DocRapSheet->user_id   = $request->userId;

            $DocRapSheet->save();
            //-----------------------------------------
        }

        //-----------------------------------------
        // User Groupe
        //-----------------------------------------
        $GroupeUser                     = new GroupeUser;

        //$GroupeUser->date_annulation    = '';
        $GroupeUser->etat               = '1';
        $GroupeUser->user_id            = $request->userId;
        $GroupeUser->groupe_id          = $Groupe->id;

        $GroupeUser->save();
        //-----------------------------------------



        //-----------------------------------------
        // UPDATE in User Info
        //-----------------------------------------
        $userInformations               = UserInfo::where('user_id', $request->userId)->first();

        $UserInfo                       = UserInfo::find($userInformations->id);

        $UserInfo->latitude             = $request->latitude;
        $UserInfo->longitude            = $request->longitude;
        $UserInfo->quartier_residence   = $request->district;
        $UserInfo->ville_residence      = $request->commune;
        $UserInfo->daira_residence      = $request->daira;
        $UserInfo->wilaya_residence     = $request->wilaya;

        $UserInfo->save();
        //-----------------------------------------


        return response()->json([
            'code'      => '201',
            'message'   => 'Inscription (etape 2) réussie.',
            'data'      => $UserInfo

        ], 201);

    }

    /** 
     * Register Next Step Shopper API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function registerNextShopper(Request $request)
    {
        $data = $request->validate(
            [
                'userId'            => 'required| integer',
                'groupeId'          => 'required| integer',
                'district'          => 'required| string',
                'commune'           => 'required| string',
                'daira'             => 'required| string',
                'wilaya'            => 'required| string',
                'latitude'          => 'required| string',
                'longitude'         => 'required| string',
                'cardId'            => 'required| image | mimes:jpeg,png,jpg |max:2048',
                'rapSheet'          => 'required| image | mimes:jpeg,png,jpg |max:2048',
            ],
            [
                'userId.required'       => 'Le champ userId est obligatoire.',
                'userId.integer'        => 'userId doite être un entier.',
                'groupeId.required'     => 'Le champ groupeId est obligatoire.',
                'groupeId.integer'      => 'groupeId doite être un entier.',
                'district.required'     => 'Le champ district est obligatoire.',
                'commune'               => 'Le champ commune est obligatoire.',
                'daira'                 => 'Le champ daira est obligatoire.',
                'wilaya'                => 'Le champ wilaya est obligatoire.',
                'latitude'              => 'Le champ latitude est obligatoire.',
                'longitude'             => 'Le champ longitude est obligatoire.',
                'cardId.required'       => 'L\'image cardId est obligatoire.',
                'rapSheet.required'     => 'L\'image rapSheet est obligatoire.',
            ]
        );

        // Card ID
        if ($request->hasFile('cardId')) {
            $CardID                 = $request->file('cardId');
            //$CardID_Name    = $request->userId.'_'.$this->generateCode().'.'.$CardID->getClientOriginalExtension();
            $CardID_Name            = $this->generateDocName() . '.' . $CardID->getClientOriginalExtension();
            $folder                 = '/users/cardid';  // Define folder path

            // Upload image
            $this->uploadOne($CardID, $folder, 'public', $CardID_Name);

            //-----------------------------------------
            // Add Card ID
            //-----------------------------------------
            $DocCardID              = new DocUser;

            $DocCardID->doc         = $CardID_Name;
            $DocCardID->etat        = '1';
            $DocCardID->user_id     = $request->userId;

            $DocCardID->save();
            //-----------------------------------------
        }

        // Rap Sheet
        if ($request->hasFile('rapSheet')) {
            $RapSheet               = $request->file('rapSheet');
            //$RapSheet_Name  = $request->userId.'_'.$this->generateCode().'.'.$RapSheet->getClientOriginalExtension();
            $RapSheet_Name          = $this->generateDocName() . '.' . $RapSheet->getClientOriginalExtension();
            $folder                 = '/users/rapSheet';  // Define folder path

            // Upload image
            $this->uploadOne($RapSheet, $folder, 'public', $RapSheet_Name);

            //-----------------------------------------
            // Add Rap Sheet
            //-----------------------------------------
            $DocRapSheet            = new DocUser;

            $DocRapSheet->doc       = $RapSheet_Name;
            $DocRapSheet->etat      = '1';
            $DocRapSheet->user_id   = $request->userId;

            $DocRapSheet->save();
            //-----------------------------------------
        }

        //-----------------------------------------
        // User Groupe
        //-----------------------------------------
        $GroupeUser                     = new GroupeUser;

        //$GroupeUser->date_annulation    = '';
        $GroupeUser->etat               = '1';
        $GroupeUser->user_id            = $request->userId;
        $GroupeUser->groupe_id          = $request->groupeId;

        $GroupeUser->save();
        //-----------------------------------------


        //-----------------------------------------
        // UPDATE in User Info
        //-----------------------------------------
        $userInformations               = UserInfo::where('user_id', $request->userId)->first();

        $UserInfo                       = UserInfo::find($userInformations->id);

        $UserInfo->latitude             = $request->latitude;
        $UserInfo->longitude            = $request->longitude;
        $UserInfo->quartier_residence   = $request->district;
        $UserInfo->ville_residence      = $request->commune;
        $UserInfo->daira_residence      = $request->daira;
        $UserInfo->wilaya_residence     = $request->wilaya;

        $UserInfo->save();
        //-----------------------------------------

        return response()->json([
            'code'      => '201',
            'message'   => 'Inscription (etape 2) réussie.',
            'data'      => $UserInfo

        ], 201);

    }

    /** 
     * Profile api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function profile(Request $request)
    {
        return 'Profile';
    }


    /** 
     * Connected User Details api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function details()
    {
        $user = Auth::user();
        $user->userInfo;
        $user->groupe;
        $user->documents;

        return response()->json([
            'code'      => '200',
            'message'   => 'Success.',
            'data'      => new UserRessource($user),
        ], 200);
    }
    
    /** 
     * Connected Teamleader Details Odrers API 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function ordersDetails()
    {
        $user = Auth::user();
        //$user->userInfo;
        //$user->groupe;
        //$user->documents;

        $shoppers = $user->groupe->shopperInGroupe;
        //$shoppers->userLocationAddress;
        
        return response()->json([
            'code'      => '200',
            'message'   => 'Success.',
            'data'      => UserRessource::collection($shoppers),
        ], 200);
    }




//------------------------------------------------------------------------------------------------//
    //
    protected function generateNumericKey()
    {
        // prefixes the key with a random integer between 1 - 9 (inclusive)
        return Keygen::numeric(31)->prefix(mt_rand(1, 9))->generate(true);
    }

    protected function generateCode()
    {
        // prefixes the key with a random integer between 1 - 9 (inclusive)
        return Keygen::numeric(6)->generate();
    }

    protected function generateCode__OLD()
    {
        return Keygen::bytes()->generate(
            function ($key) {
                // Generate a random numeric key
                $random = Keygen::numeric()->generate();

                // Manipulate the random bytes with the numeric key
                return substr(md5($key . $random . strrev($key)), mt_rand(0, 8), 6);
            }
        );
    }

    /** 
     * generate Document Name api 
     * 
     * @return \Illuminate\Http\Response 
     */
    protected function generateDocName()
    {
        $documentName = $this->generateNumericKey();

        // Ensure ID does not exist
        // Generate new one if ID already exists
        while (DocUser::where('doc', $documentName)->count() > 0) {
            $documentName = $this->generateNumericKey();
        }

        return $documentName;
    }
}
