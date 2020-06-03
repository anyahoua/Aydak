<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Models\UserInfo;
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
            /*
                //$user = $this->guard()->user();
                $user = Auth::user();
                $token =  $user->createToken('AydakUsers')->accessToken;
                
                $user->apitoken = $token;
                $user->userInfo;
                $user->userInfo->profil;
                //$user->profil;

                return response()->json([
                    'code'      => '200',
                    'message'   => 'Authentification réussie.',
                    //'data'      => new UserLoginResource($user)
                    //'apiToken'  => $token,
                    'data'      => $user,
                    
                ], 200);
            */

            $user = Auth::user();

            if ($user->userInfo->etat == 1) {
                $token =  $user->createToken('AydakUsers')->accessToken;

                $user->apitoken = $token;
                $user->userInfo;
                $user->userInfo->profil;
                //$user->profil;

                return response()->json([
                    'code'      => '200',
                    'message'   => 'Authentification réussie.',
                    //'data'      => new UserLoginResource($user)
                    //'apiToken'  => $token,
                    'data'      => $user,

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

        // Get Teamleader :
        //-----------------------------
        $Teamleader = User::find($request->userId);
        $Teamleader->groupeUser;
        $Teamleader->groupe;
        $Teamleader->invitations;
        $Teamleader->documents;

        // Generate code : 
        //-----------------------------
        $code = $this->generateCode();

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
        $Invitation->user;
        $Invitation->groupe;

        $count = $Invitation ? $query->count() : 0;

        if ($count == 1) {
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
/*
        $validator = Validator::make($request->all(), [
            'lastname'      => 'required',  // nom
            'firstname'     => 'required',  // prenom
            'address'       => 'required | string',
            'profil'        => 'required | integer',
            'username'      => ['required', 'regex:/^(05|06|07)[0-9]{8}$/', 'unique:users'], // Mobile
            'password'      => 'required',
            'c_password'    => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
*/
        // Validate input request.
        $this->validator($request->all())->validate();

        // Input data user :
        $data = [
            'nom'       => $request->lastname,
            'prenom'    => $request->firstname,
            'username'  => $request->username,
            'password'  => bcrypt($request->password)
        ];

        // Add User :
        $user   = User::create($data);
        //--

        $token  =  $user->createToken('AydakUsers')->accessToken;

        $user->apitoken = $token;

        // Add User infos :
        $userInfos                      = new UserInfo;

        $userInfos->mobile              = $request->username;
        $userInfos->adresse_residence   = $request->address;
        $userInfos->user_id             = $user->id;
        $userInfos->profil_id           = $request->profil;
        $userInfos->etat                = '1';
        $userInfos->etape               = '1';
        $UserInfo->pays_residence       = 'Algérie';

        $userInfos->save();

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
        $Groupe->photo          = '';
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
        /*
        $validator = Validator::make($request->all(), [ 
            //'nom'           => 'required', 
            //'prenom'        => 'required', 
            //'username'      => 'required | unique:users', 
            //'password'      => 'required', 
            //'c_password'    => 'required|same:password', 

        ]);
        
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

//public_path().'/images/'
*/
        /*
        $data = $request->validate([
            'avatarImage'              => 'required| image | mimes:jpeg,png,jpg |max:2048',
        ],
        [
            'avatarImage.required'     => 'L\'image Avatar est obligatoire.',
        ]);
        */

        //-------------------------------//
        // Upload file image
        //-------------------------------//
        $folder             = '/ecgs';  // Define folder path

        if ($request->hasFile('avatarImage')) {

            //return $request->file('avatarImage');

            foreach ($request->file('avatarImage') as $avatar) {
                $New_Image_Name[]     = time() . '.' . $avatar->getClientOriginalExtension();
                $name = $avatar->getClientOriginalName();

                //$avatar->move(public_path().'images/', $name);
                $this->uploadOne($avatar, $folder, 'public', $name);

                $data_name[] = $name;

                //$this->uploadOne($avatar, $folder, 'public', $New_Image_Name);
            }

            return $data_name;
        }

        /*
        if($request->hasFile('avatarImage'))
        {

            foreach($request->file('avatarImage') as $file)
            {
              $filePath[] = $this->UserImageUploadTrait($file); //passing parameter to our trait method one after another using foreach loop

                //Image::create([
                //  'image' => $filePath,
                //]);

            }

            //Toastr::success('Image uploaded successfully :)','Success');
        }
*/
        return response()->json([
            'code'      => '201',
            'message'   => 'Profile updated successfully.',
            //'data'      => $filePath

        ], 201);
    }


    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function details()
    {
        $user = Auth::user();

        return response()->json([
            'code'      => '200',
            'message'   => 'Success.',
            'data'      => $user
        ], 200);
    }


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
