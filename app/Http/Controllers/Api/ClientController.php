<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Models\Groupe;
use App\Models\ClientInfo;
use App\Models\ClientCompte;
use App\Models\ClientPreferenceAchat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\UploadTrait;

use Validator;
use Keygen;

class ClientController extends Controller
{
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){ 

        $input = $request->all();

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);


        if(auth::guard('client')->attempt(array('username' => $input['username'], 'password' => $input['password'])))
        { 
            
            /*
                //$user = $this->guard()->user();
                //$client = auth::guard('client');
                $client = auth::guard('client')->user();
                $token =  $client->createToken('AydakClients')->accessToken;
                
                $client->apitoken = $token;

                return response()->json([
                    'code'      => '200',
                    'message'   => 'Authentification client réussie.',
                    //'data'      => new UserLoginResource($user)
                    //'apiToken'  => $token,
                    'data'      => $client
                    
                ], 200);
            */
            
            $client = auth::guard('client')->user();
            
            if($client->ClientInfo->etat==1)
            {
                $token =  $client->createToken('AydakClients')->accessToken;
                
                $client->apitoken = $token;
                $client->ClientInfo;
                
                //--
                $client->clientCompte;
                $client->clientPpreferenceAchat;
                $client->commandes;
                //--
                
                return response()->json([
                    'code'      => '200',
                    'message'   => 'Authentification client réussie.',
                    //'data'      => new UserLoginResource($user)
                    //'apiToken'  => $token,
                    'data'      => $client
                ], 200);

            } else {
                return response()->json(['error'=>'Unauthorised status'], 401); 
            }


        } else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
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
            'groupeId'      => ['required', 'integer'],
            'username'      => ['required', 'regex:/^(05|06|07)[0-9]{8}$/', 'unique:clients'], // Mobile
            //'password'      => ['required', 'string', 'min:8', 'confirmed'], //---> password_confirmation = 'le mot de passe'
            'password'      => ['required', 'string', 'min:8'],
            'c_password'    => ['required', 'same:password'],
        ]);

    }

    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
/*
        $validator = Validator::make($request->all(), [ 
            'nom'           => 'required', 
            'prenom'        => 'required', 
            'username'      => 'required | unique:clients', 
            'password'      => 'required', 
            'c_password'    => 'required|same:password', 
            'groupe_id'     => 'required', 
        ]);
        
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']);


        $client = Client::create($input);
        $token  =  $client->createToken('AydakClients')->accessToken;

        $client->apitoken = $token;
*/
        // Validate input request.
        $this->validator($request->all())->validate();

        // Input data user :
        $data = [
            'nom'       => $request->lastname,
            'prenom'    => $request->firstname,
            'username'  => $request->username,
            'password'  => bcrypt($request->password),
            'groupe_id' => $request->groupeId,
        ];

        // Add client :
        $client   = Client::create($data);
        //--

        $token  =  $client->createToken('AydakClients')->accessToken;

        $client->apitoken = $token;


        return response()->json([
            'code'      => '201',
            'message'   => 'Inscription client réussie.',
            'data'      => $client,
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
        //return response()->json(['success' => $user], $this->successStatus); 

        return response()->json([
            'code'      => '200',
            'message'   => 'Success.',
            'data'      => $user
        ], 200);
    }








}
