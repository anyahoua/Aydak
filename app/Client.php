<?php

namespace App;

use App\Models\ClientInfo;
use App\Models\ClientCompte;
use App\Models\ClientPreferenceAchat;
use App\Models\Commande;

use Laravel\Passport\HasApiTokens;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // The authentication guard for client
    protected $guard = 'client';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'prenom', 'username', 'password', 'groupe_id', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    /*
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    */


    /** 
     * Relationship : 
     * 
     * */

    public function clientInfo()
    {
        return $this->hasOne(ClientInfo::class);
    }

    public function clientCompte()
    {
        return $this->hasOne(ClientCompte::class);
    }

    public function clientPpreferenceAchat()
    {
        return $this->hasOne(ClientPreferenceAchat::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }


    
}