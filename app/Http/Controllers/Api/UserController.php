<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Models\UserInfo;
use App\Models\DocUser;
use App\Models\GroupeUser;
use App\Models\InvitationShopper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Keygen;
use App\Traits\UploadTrait;

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
        if(auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password'])))
        { 
            /*
                //$user = $this->guard()->user();
                $user = Auth::user();
                $token =  $user->createToken('AydakUsers')->accessToken;
                
                $user->apitoken = $token;
                $user->userInfo;
                $user->userInfo->Profil;
                //$user->Profil;

                return response()->json([
                    'code'      => '200',
                    'message'   => 'Authentification réussie.',
                    //'data'      => new UserLoginResource($user)
                    //'apiToken'  => $token,
                    'data'      => $user,
                    
                ], 200);
            */

            $user = Auth::user();
            
            if($user->userInfo->etat==1)
            {
                $token =  $user->createToken('AydakUsers')->accessToken;
                
                $user->apitoken = $token;
                $user->userInfo;
                $user->userInfo->Profil;
                //$user->Profil;

                return response()->json([
                    'code'      => '200',
                    'message'   => 'Authentification réussie.',
                    //'data'      => new UserLoginResource($user)
                    //'apiToken'  => $token,
                    'data'      => $user,
                    
                ], 200);

            } else {
                return response()->json(['error'=>'Unauthorised status'], 401); 
            }



            
        } else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

    /** 
     * Invitation Shopper API
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function InvitationShopper(Request $request) 
    {

        $data = $request->validate([
            'userId'            => 'required| integer ',
            'mobile'            => ['required', 'regex:/^(05|06|07)[0-9]{8}$/'],
        ],
        [
            'userId.required'   => 'Le champ userId est obligatoire.',
            'userId.integer'    => 'userId doite être un entier.',
            'mobile.required'   => 'Le champ mobile est obligatoire.',
            'regex.required'    => 'Le champ mobile est invalide.',
        ]);

        

        $Teamleader = User::find($request->userId);
        $Teamleader->groupeUser;
        $Teamleader->groupe;
        $Teamleader->invitations;
        $Teamleader->documents;

        //return $Teamleader;

        // Generate code : 
        $code = $this->generateCode();

        // Add Invitation Shopper :
        $Invitation                     = new InvitationShopper;

        $Invitation->mobile             = $request->mobile;
        $Invitation->code               = $code;
        $Invitation->date_envoie        = '';
        $Invitation->date_activation    = '';
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
        $data = $request->validate([
            'code'              => 'required| integer ',
            'mobile'            => ['required', 'regex:/^(05|06|07)[0-9]{8}$/'],
        ],
        [
            'code.required'     => 'Le champ code est obligatoire.',
            'mobile.required'   => 'Le champ mobile est obligatoire.',
            'regex.required'    => 'Le champ mobile est invalide.',
        ]);

        $Invitation = InvitationShopper::where('code', $request->code)->where('mobile', $request->mobile)->where('etat', '0')->first();
        
        if($Invitation->count()==1)
        {
            return response()->json([
                'code'      => '200',
                'message'   => 'Le coursier à bien été invité.',
                'data'      => $Invitation
                
            ], 200);
        }

        return response()->json(['error'=>'unauthorized code'], 401);
    }


    /** 
     * Register First Step Teamleader And Shopper API
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
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
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        // Add User :
        $data = [
            'nom'       => $request->lastname,
            'prenom'    => $request->firstname,
            'username'  => $request->username,
            'password'  => bcrypt($request->password)
        ];

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

        $userInfos->save();

        //
        $user->userInfo;

        return response()->json([
            'code'      => '201',
            'message'   => 'Inscription réussie.',
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
        $data = $request->validate([
            'userId'            => 'required| integer ',
            'groupeId'          => 'required| integer ',
            'district'          => 'required| string ',
            'cardId'            => 'required| image | mimes:jpeg,png,jpg |max:2048',
            'rapSheet'          => 'required| image | mimes:jpeg,png,jpg |max:2048',
        ],
        [
            'userId.required'   => 'Le champ userId est obligatoire.',
            'userId.integer'    => 'userId doite être un entier.',
            'groupeId.required'   => 'Le champ groupeId est obligatoire.',
            'groupeId.integer'    => 'groupeId doite être un entier.',
            'district.required'   => 'Le champ district est obligatoire.',
            'cardId.required'   => 'L\'image cardId est obligatoire.',
            'rapSheet.required' => 'L\'image rapSheet est obligatoire.',
        ]);
        
        // Card ID
        if($request->hasFile('cardId'))
        {
            $CardID                 = $request->file('cardId');
            //$CardID_Name    = $request->userId.'_'.$this->generateCode().'.'.$CardID->getClientOriginalExtension();
            $CardID_Name            = $this->generateDocName().'.'.$CardID->getClientOriginalExtension();
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
        if($request->hasFile('rapSheet'))
        {
            $RapSheet               = $request->file('rapSheet');
            //$RapSheet_Name  = $request->userId.'_'.$this->generateCode().'.'.$RapSheet->getClientOriginalExtension();
            $RapSheet_Name          = $this->generateDocName().'.'.$RapSheet->getClientOriginalExtension();
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

        $GroupeUser->date_annulation    = '';
        $GroupeUser->etat               = '1';
        $GroupeUser->user_id            = $request->userId;
        $GroupeUser->groupe_id          = $request->groupeId;

        $GroupeUser->save();
        //-----------------------------------------


        /*
        //-----------------------------------------
        // User Info
        //-----------------------------------------
        $UserInfo                       = new UserInfo;
        
        $UserInfo->mobile               = '';
        $UserInfo->quartier             = '';
        $UserInfo->latitude             = '';
        $UserInfo->longitude            = '';
        $UserInfo->deg2rad_longitude    = '';
        $UserInfo->deg2rad_latitude     = '';
        $UserInfo->quartier_livraison   = '';
        $UserInfo->ville_livraison      = '';
        $UserInfo->daira_livraison      = '';
        $UserInfo->wilaya_livraison     = '';
        $UserInfo->pays_livraison       = '';
        $UserInfo->adresse_residence    = '';
        $UserInfo->quartier_residence   = '';
        $UserInfo->ville_residence      = '';
        $UserInfo->daira_residence      = '';
        $UserInfo->wilaya_residence     = '';
        $UserInfo->pays_residence       = '';
        $UserInfo->user_id              = $request->userId;
        $UserInfo->profil_id            = '';
        $UserInfo->etat                 = '1';
        $UserInfo->etape                = '2';
        //-----------------------------------------
        */




    }

    /** 
     * Register Next Step Shopper API
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function registerNextShopper(Request $request) 
    {
        $data = $request->validate([
            'userId'            => 'required| integer ',
            'groupeId'          => 'required| integer ',
            'district'          => 'required| string ',
            'cardId'            => 'required| image | mimes:jpeg,png,jpg |max:2048',
            'rapSheet'          => 'required| image | mimes:jpeg,png,jpg |max:2048',
        ],
        [
            'userId.required'   => 'Le champ userId est obligatoire.',
            'userId.integer'    => 'userId doite être un entier.',
            'groupeId.required'   => 'Le champ groupeId est obligatoire.',
            'groupeId.integer'    => 'groupeId doite être un entier.',
            'district.required'   => 'Le champ district est obligatoire.',
            'cardId.required'   => 'L\'image cardId est obligatoire.',
            'rapSheet.required' => 'L\'image rapSheet est obligatoire.',
        ]);
        
        // Card ID
        if($request->hasFile('cardId'))
        {
            $CardID                 = $request->file('cardId');
            //$CardID_Name    = $request->userId.'_'.$this->generateCode().'.'.$CardID->getClientOriginalExtension();
            $CardID_Name            = $this->generateDocName().'.'.$CardID->getClientOriginalExtension();
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
        if($request->hasFile('rapSheet'))
        {
            $RapSheet               = $request->file('rapSheet');
            //$RapSheet_Name  = $request->userId.'_'.$this->generateCode().'.'.$RapSheet->getClientOriginalExtension();
            $RapSheet_Name          = $this->generateDocName().'.'.$RapSheet->getClientOriginalExtension();
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

        $GroupeUser->date_annulation    = '';
        $GroupeUser->etat               = '1';
        $GroupeUser->user_id            = $request->userId;
        $GroupeUser->groupe_id          = $request->groupeId;

        $GroupeUser->save();
        //-----------------------------------------


        /*
        //-----------------------------------------
        // User Info
        //-----------------------------------------
        $UserInfo                       = new UserInfo;
        
        $UserInfo->mobile               = '';
        $UserInfo->quartier             = '';
        $UserInfo->latitude             = '';
        $UserInfo->longitude            = '';
        $UserInfo->deg2rad_longitude    = '';
        $UserInfo->deg2rad_latitude     = '';
        $UserInfo->quartier_livraison   = '';
        $UserInfo->ville_livraison      = '';
        $UserInfo->daira_livraison      = '';
        $UserInfo->wilaya_livraison     = '';
        $UserInfo->pays_livraison       = '';
        $UserInfo->quartier_residence   = '';
        $UserInfo->ville_residence      = '';
        $UserInfo->daira_residence      = '';
        $UserInfo->wilaya_residence     = '';
        $UserInfo->pays_residence       = '';
        $UserInfo->user_id              = $request->userId;
        $UserInfo->profil_id            = '';
        $UserInfo->etat                 = '1';
        $UserInfo->etape                = '2';
        //-----------------------------------------
        */




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

        if($request->hasFile('avatarImage'))
        {
            
            //return $request->file('avatarImage');
            
            foreach($request->file('avatarImage') as $avatar)
            {
                $New_Image_Name[]     = time().'.'.$avatar->getClientOriginalExtension();
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
            function($key) {
                // Generate a random numeric key
                $random = Keygen::numeric()->generate();

                // Manipulate the random bytes with the numeric key
                return substr(md5($key . $random . strrev($key)), mt_rand(0,8), 6);
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
