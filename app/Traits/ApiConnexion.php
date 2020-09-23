<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\UserConnexion;
use App\Models\UserInfo;

trait ApiConnexion{

    protected function userConnexion($data)
	{
        //return $data['userId'];

        $userConnexion              = new UserConnexion;
        
        $userConnexion->user_id     = $data['userId'];
        $userConnexion->action      = $data['action'];
        $userConnexion->profil_id   = $data['profil_id'];

        $userConnexion->save();

        //return $userConnexion;

        //-----------
        
        UserInfo::where('user_id', $data['userId'])->update([ 'current_profil_id' => $data['profil_id'] ]);


        return true;
    }
    

}