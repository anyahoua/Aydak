<?php

namespace App\Http\Controllers\Api;

use Laravel\Passport\Client as OClient;
use App\User;
use App\Models\UserAdresse;
use App\Models\UserInfo;
use App\Models\UserConnexion;
use App\Models\DocUser;
use App\Models\GroupeUser;
use App\Models\Groupe;
use App\Models\InvitationShopper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

//use GuzzleHttp\Client;
//use Illuminate\Support\Facades\Http;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;


use App\Traits\UploadTrait;
use Validator;
use Keygen;

use App\Http\Resources\Api\UserLoginRessource;
use App\Http\Resources\Api\UserRessource;
use App\Http\Resources\Api\OrdersRessource;

class UserController extends ApiController
//class UserController extends Controller
{
    use UploadTrait;

    /** 
     * Login API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function login(Request $request)
    {
        // $data = ['userId' => '1', 'action' => 'connexion', 'profil_id' => '2'];
        // return $this->userConnexion($data);

        $input = $request->all();

        // $this->validate($request, [
        //     'username' => 'required',
        //     'password' => 'required',
        // ]);

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }


        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password']))) {

            $user = Auth::user();

            if ($user->userInfo->etat == 1) {
/*
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
*/
                //---------------------------------------

                //$oClient = OClient::where('password_client', 1)->first();
                $oClient = OClient::find(2);

                $fullToken = $this->getTokenAndRefreshToken($oClient, $request->username, $request->password);

                $user->access_token     = $fullToken['access_token'];
                $user->refresh_token    = $fullToken['refresh_token'];
                $user->expires_in       = $fullToken['expires_in'];
                //$user->userInfo;
                //$user->userInfo->profil;
                $user->userLocationAddress;
                $user->userLocationAddress->pays;

                //return $user->isTeamleader;

                //-------------------
                // Connexion History
                //-------------------
                $data = ['userId' => $user->id, 'action' => 'Connexion', 'profil_id' => '1'];

                $this->userConnexion($data);
                //-------------------
                
                return $this->successResponse(new UserLoginRessource($user));
                //---------------------------------------



            } else {
                return $this->errorResponse('Unauthorised status', 401);
                //return response()->json(['error' => 'Unauthorised status'], 401);
            }
        } else {
            return $this->errorResponse('Unauthorised', 401);
            //return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /* Generate passport token and refresh token */
    public function getTokenAndRefreshToken(OClient $oClient, $username, $password)
    { 

        $http = new \GuzzleHttp\Client();

        $url = route('passport.token');
        
        $response = $http->post($url, [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $username,
                'password' => $password,
                'scope' => '*',
            ],
            'http_errors' => false // add this to return errors in json
        ]);

        return json_decode((string) $response->getBody(), true);

        // $result = json_decode((string) $response->getBody(), true);
        // return response()->json($result, 200);
    }

    public function refreshToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required',
        ]);
        
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }

        try {

            $oClient = OClient::find(2);

            $http = new \GuzzleHttp\Client();
    
            $url = route('passport.token');
            
            $response = $http->post($url, [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $request->refresh_token,
                    'client_id' => $oClient->id,
                    'client_secret' => $oClient->secret,
                    'scope' => '',
                ],
                //'http_errors' => false // add this to return errors in json
            ]);
    
            $myReponse = collect( json_decode((string) $response->getBody(), true) );

            return $this->successResponse($myReponse);

        } catch (RequestException $e) {
              
            if ($e->hasResponse()) {

                $statusCode   = $e->getResponse()->getStatusCode();
                $json_reponse = json_decode($e->getResponse()->getBody(true), true);
                $message      = $json_reponse['message'];

                return $this->errorResponse($message, $statusCode);
            }

        }


    }

    /** 
     * Switch Login API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function switchLogin(Request $request)
    {

        $user = $request->User();
        //$user = Auth::user();

        $ts = $user->userInfo->teamleader_shopper;

        if($ts==1)
        {
            $userId = $user->id;
            
            if($user->userProfil->profil_id == 1)
            {
                $profilId   = '2';
                $action     = 'Switch to Shopper';

            } else {
                $profilId   = '1';
                $action     = 'Switch to Teamleader';
            }
            

            //S'il est activer
            if ($user->userInfo->etat == 1)
            {
                $oClient = OClient::find(2);

                $fullToken = $this->getTokenAndRefreshToken($oClient, $request->username, $request->password);

                $user->access_token     = $fullToken['access_token'];
                $user->refresh_token    = $fullToken['refresh_token'];
                $user->expires_in       = $fullToken['expires_in'];
                $user->userLocationAddress;
                $user->userLocationAddress->pays;
            
                //-------------------
                // Connexion History
                //-------------------
                $data = ['userId' => $userId, 'action' => $action, 'profil_id' => $profilId];

                $this->userConnexion($data);
                //-------------------            
            
                return $this->successResponse(new UserLoginRessource($user));

            } else {
                return $this->errorResponse('Unauthorised status', 401);
            }

        }
        
        return $this->errorResponse('Unauthorised!.', 403);
    }



    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {

        $request->user()->token()->revoke();

        //---------------------
        // Deconnexion History
        //---------------------
        // $data = ['userId' => $user->id, 'action' => 'Deconnexion', 'profil_id' => ?? ];

        // $this->userConnexion($data);
        //---------------------
        
        return $this->successResponse($data=null, 'Successfully logged out');
    }

    /** 
     * Invitation Shopper API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function InvitationShopper(Request $request)
    {

        $validator = Validator::make($request->all(), 
            [
                // 'userId'            => 'required| integer ',
                'mobile'            => ['required', 'regex:/^(05|06|07)[0-9]{8}$/'],
            ],
            [
                // 'userId.required'   => 'Le champ userId est obligatoire.',
                // 'userId.integer'    => 'userId doite être un entier.',
                'mobile.required'   => 'Le champ mobile est obligatoire.',
                'regex.required'    => 'Le champ mobile est invalide.',
            ]
        );
        
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }


        // If Mobile exist
        $coursier = User::where('username', $request->mobile)->first();
        if(!empty($coursier)){
            //return response()->json(['error' => 'Unauthorised!. This mobile exist.'], 401);
            return $this->errorResponse('Unauthorised!. Ce numéro de mobile existe déjà.', 401);
        }


        // Get Teamleader :
        //-----------------------------
        $Teamleader = $request->user();

        if(!empty($Teamleader)){
        
            $profilId = $Teamleader->userInfo->profil->id;

            if($profilId == '1'){

                // Generate code : 
                //-----------------------------
                $generatedCode = $this->generateCode();
                
                // Disable All Old Invitations Shopper :
                //-----------------------------
                InvitationShopper::where('mobile', $request->mobile)->where('etat', '0')->update(['etat' => '1']);

                // Add Invitation Shopper :
                //-----------------------------
                $Invitation                     = new InvitationShopper;

                $Invitation->mobile             = $request->mobile;
                $Invitation->code               = $generatedCode;
                //$Invitation->date_activation    = '';
                $Invitation->etat               = '0';
                $Invitation->user_id            = $Teamleader->id;
                $Invitation->groupe_id          = $Teamleader->groupeUser->groupe_id;

                $Invitation->save();
            } else {
                //return response()->json(['error' => 'Unauthorised for this action.'], 401);
                return $this->errorResponse('Unauthorised for this action.', 401);
            }

        } else {
            //return response()->json(['error' => 'User not found.'], 404);
            return $this->errorResponse('Teamleader not found.', 401);
        }

        return $this->successResponse($Invitation, 'Invitation à bien été envoyée.');
    }

    /*
    public function InvitationShopper____OLD(Request $request)
    {

        // $user = $request->user();
        // $user->userInfo;
        // //$user->userInfo->profil;
        // $user->userLocationAddress;
        // $user->userLocationAddress->pays;

        // return $user;

        $user = $request->user();
        //$user->userInfo;
        $pr = $user->userInfo->profil->id;
        //$user->userLocationAddress;
        //$user->userLocationAddress->pays;

        return $pr;

        // $data = $request->validate(
        //     [
        //         'userId'            => 'required| integer ',
        //         'mobile'            => ['required', 'regex:/^(05|06|07)[0-9]{8}$/'],
        //     ],
        //     [
        //         'userId.required'   => 'Le champ userId est obligatoire.',
        //         'userId.integer'    => 'userId doite être un entier.',
        //         'mobile.required'   => 'Le champ mobile est obligatoire.',
        //         'regex.required'    => 'Le champ mobile est invalide.',
        //     ]
        // );

        $validator = Validator::make($request->all(), 
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
        
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }


        // If Mobile exist
        $coursier = User::where('username', $request->mobile)->first();
        if(!empty($coursier)){
            //return response()->json(['error' => 'Unauthorised!. This mobile exist.'], 401);
            return $this->errorResponse('Unauthorised!. Ce numéro de mobile existe déjà.', 401);
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
    */


    /** 
     * Validate Code For Shopper API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function validateCode(Request $request)
    {

        $validator = Validator::make($request->all(), 
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
        
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }


        $query = InvitationShopper::where('code', $request->code)->where('mobile', $request->mobile)->where('etat', '0');

        $Invitation = $query->first();

        if(!empty($Invitation))
        {
            $Invitation->user;
            $Invitation->groupe;

            return $this->successResponse($Invitation, 'Le coursier à bien été invité.');
        }
        
        return $this->errorResponse('Unauthorized code', 401);
    }


    /** 
     * Register First Step Teamleader And Shopper API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lastName'      => ['required', 'string', 'max:255'],
            'firstName'     => ['required', 'string', 'max:255'],
            'address'       => ['required', 'string', 'max:255'],
            'profil'        => ['required', 'integer'],
            'username'      => ['required', 'regex:/^(05|06|07)[0-9]{8}$/', 'unique:users'], // Mobile
            //'password'      => ['required', 'string', 'min:8', 'confirmed'], //---> password_confirmation = 'le mot de passe'
            'password'      => ['required', 'string', 'min:8'],
            'c_password'    => ['required', 'same:password'],
        ]);
        
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }

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

        $token  = $user->createToken('AydakUsers')->accessToken;

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

/*
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
        $UserLocationAddress->user_id       = $user->id;
        $UserLocationAddress->etat          = '1';

        $UserLocationAddress->save();
*/
        //
        $user->userInfo;

        return $this->successResponse($user, 'Inscription (etape 1) réussie.', 201);
    }


    /** 
     * Register Next Step Teamleader API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function registerNextTeamleader(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'userId'            => 'required| integer',
                'isLeader'          => 'required| integer',
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
                'isLeader.required'     => 'Le champ isLeader est obligatoire.',
                'isLeader.integer'      => 'isLeader doite être un entier.',
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

        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }

        //-----------------------------------------
        // UPDATE in User Info
        //-----------------------------------------
        $UserInfo                       = UserInfo::findOrFail($request->userId);
        $UserInfo->teamleader_shopper   = $request->isLeader;
        $UserInfo->etape                = '2';
        
        $UserInfo->save();
        //-----------------------------------------


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


        // Add User Location Address :
        //-----------------------------------------
        $UserLocationAddress                = new UserAdresse;
        
        $UserLocationAddress->latitude      = $request->latitude;
        $UserLocationAddress->longitude     = $request->longitude;
        $UserLocationAddress->quartier      = $request->district;
        $UserLocationAddress->commune       = $request->commune;
        $UserLocationAddress->daira         = $request->daira;
        $UserLocationAddress->wilaya        = $request->wilaya;
        $UserLocationAddress->pays_id       = config('global.country_id');
        $UserLocationAddress->user_id       = $request->userId;
        $UserLocationAddress->etat          = '1';

        $UserLocationAddress->save();
        //-----------------------------------------

        return $this->successResponse($UserInfo, 'Inscription (etape 2) réussie.', 201);

    }

    /** 
     * Register Next Step Shopper API
     * 
     * @return \Illuminate\Http\Response 
     */
    public function registerNextShopper(Request $request)
    {
        $validator = Validator::make($request->all(), 
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

        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }

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

        return $this->successResponse($UserInfo, 'Inscription (etape 2) réussie.', 201);
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

        return $this->successResponse(new UserRessource($user), 'User Détails');
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
        
        return $this->successResponse(UserRessource::collection($shoppers), 'Orders details');
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
