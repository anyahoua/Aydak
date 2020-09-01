<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Models\ClientAdresse;
use App\Models\Groupe;
use App\Models\ClientInfo;
use App\Models\ClientCompte;
use App\Models\ClientPreferenceAchat;
use App\Models\Commande;
use App\Models\CommandeDetail;
use App\Models\CommandeCommentaire;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\UploadTrait;

use App\Http\Resources\Api\Clients\ClientLoginRessource;
use App\Http\Resources\Api\Clients\ClientDataRessource;
use App\Http\Resources\Api\Clients\ClientCompteRessource;
use App\Http\Resources\Api\Clients\ClientOrdersRessource;

use Validator;
use Keygen;

//class ClientController extends Controller
class ClientController extends ApiController
{
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){ 

        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }


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
                //$client->ClientInfo;
                
                //--
                $client->clientCompte;
                $client->clientPreferenceAchat;
                $client->commandes;
                $client->clientLocationAddress;
                //--
                
                return $this->successResponse(new ClientLoginRessource($client), 'Authentification client réussie.');

            } else {
                return $this->errorResponse('Unauthorised status', 401);
                //return response()->json(['error'=>'Unauthorised status'], 401);
            }


        } else{
            return $this->errorResponse('Unauthorised', 401);
            //return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

    /**
     * Logout client (Revoke the token)
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
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'lastName'      => ['required', 'string', 'max:255'],
            'firstName'     => ['required', 'string', 'max:255'],
            'groupeId'      => ['required', 'integer'],
            'username'      => ['required', 'regex:/^(05|06|07)[0-9]{8}$/', 'unique:clients'], // Mobile
            //'password'      => ['required', 'string', 'min:8', 'confirmed'], //---> password_confirmation = 'le mot de passe'
            'password'      => ['required', 'string', 'min:8'],
            'c_password'    => ['required', 'same:password'],

            'district'          => ['required', 'string'],
            'commune'           => ['required', 'string'],
            'daira'             => ['required', 'string'],
            'wilaya'            => ['required', 'string'],
            'latitude'          => ['required', 'numeric'],
            'longitude'         => ['required', 'numeric'],
        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }

        // Input data user :
        $data = [
            'nom'       => $request->lastName,
            'prenom'    => $request->firstName,
            'username'  => $request->username,
            'password'  => bcrypt($request->password),
            'groupe_id' => $request->groupeId,
        ];

        // Add client :
        //-------------------
        $client   = Client::create($data);
        //--

        $token  =  $client->createToken('AydakClients')->accessToken;

        $client->apitoken = $token;

        // Add Client infos :
        //-------------------
        $clientInfos                    = new ClientInfo;

        $clientInfos->mobile            = $request->username;
        $clientInfos->client_id         = $client->id;
        $clientInfos->etat              = '0';

        $clientInfos->save();

        // Add Client Location Address :
        //------------------------------
        $clientLocationAddress                  = new ClientAdresse;
        
        $clientLocationAddress->latitude        = $request->latitude;
        $clientLocationAddress->longitude       = $request->longitude;
        $clientLocationAddress->quartier        = $request->district;
        $clientLocationAddress->commune         = $request->commune;
        $clientLocationAddress->daira           = $request->daira;
        $clientLocationAddress->wilaya          = $request->wilaya;
        $clientLocationAddress->pays_id         = config('global.country_id');
        $clientLocationAddress->client_id       = $client->id;
        $clientLocationAddress->etat            = '1';

        $clientLocationAddress->save();        

/*
        // Add Client Compte :
        //--------------------
        $clientCompte                  = new ClientCompte;
        
        $clientCompte->debit            = '0';
        $clientCompte->credit           = '0';
        $clientCompte->ancien_solde     = '0';
        $clientCompte->nouveau_solde    = '0';
        $clientCompte->etat             = '1';
        $clientCompte->client_id        = $client->id;
        $clientCompte->groupe_id        = $request->groupeId;

        $clientCompte->save();
*/


        //
        $client->clientInfos;
        $client->clientLocationAddress;
        $client->clientCompte;

        return $this->successResponse(new ClientDataRessource($client), 'Inscription réussie. Un Team-Leader va vous contacter bientot afin de recueillir votre prépaiement.', 201);
    }

    /** 
     * Connected Client Details API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $client = Auth::user(); 
        
        return $this->successResponse(new ClientLoginRessource($client), 'Successfully');
    }

    /*
    |-------------------------------------------------------------------------------
    | Client Account
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/clients/productFavoritsListe
    | Method:         GET
    | Description:    Show Connected Client Account API.
    */
    public function myAccount() 
    { 
        $client = Auth::user(); 

        //return $client->clientCompte;
        return $this->successResponse(new ClientCompteRessource($client->clientCompte), 'Successfully');
    }

    /** 
     * Connected Client Account tHistory API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function myAccountHistory() 
    { 
        $client = Auth::user(); 

        return $this->successResponse($client->clientCompteHistory, 'Successfully');
    }


    /** 
     * Connected Client : Contact Team Leader API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function ContactTeamleader() 
    { 
        $client = Auth::user(); 
        
        return $this->successResponse($client->groupe->TeamleaderInGroupe, 'Successfully');
    }


    /** 
     * Connected Client Show Current Orders API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function myCurrentOrders() 
    { 
        $client = Auth::user();

        return $this->successResponse(ClientOrdersRessource::collection($client->commandes), 'Successfully');
    }






}
