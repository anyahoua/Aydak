<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;

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

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if(auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password'])))
        { 
            //$user = $this->guard()->user();
            $user = Auth::user();
            $token =  $user->createToken('AydakUsers')->accessToken;
            
            $user->apitoken = $token;

            return response()->json([
                'code'      => '200',
                'message'   => 'Authentification réussie.',
                //'data'      => new UserLoginResource($user)
                //'apiToken'  => $token,
                'data'      => $user
                
            ], 200);

        } else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }




    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'nom'           => 'required', 
            'prenom'        => 'required', 
            'username'      => 'required', 
            'password'      => 'required', 
            'c_password'    => 'required|same:password', 
        ]);
        
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']);

        /*
        $user = User::create($input);
        $success['token']   =  $user->createToken('AydakUsers')->accessToken; 
        $success['name']    =  $user->name;

        return response()->json(['success'=>$success], $this-> successStatus); 
        */
        $user   = User::create($input);
        $token  =  $user->createToken('AydakUsers')->accessToken;

        $user->apitoken = $token;

        return response()->json([
            'code'      => '201',
            'message'   => 'Inscription réussie.',
            'data'      => $user
            
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
