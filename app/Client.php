<?php

namespace App;

use App\Models\ClientAdresse;
use App\Models\ClientInfo;
use App\Models\ClientCompte;
use App\Models\ClientPreferenceAchat;
use App\Models\Commande;
use App\Models\Groupe;

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
    public function findForPassport($identifier) {
        return $this->orWhere('username', $identifier)->first();
    }
    
    public function clientLocationAddress()
    {
        return $this->hasOne(ClientAdresse::class);
    }

    // Client informations detail
    public function clientInfo()
    {
        return $this->hasOne(ClientInfo::class);
    }
    
    // Client Compte
    public function clientCompte()
    {
        return $this->hasOne(ClientCompte::class)->where('etat','1')->where('type', 'verssement');
    }

    // Client Compte History
    public function clientCompteHistory()
    {
        return $this->hasMany(ClientCompte::class)->orderBy('id', 'DESC');
    }

    public function clientPreferenceAchat()
    {
        return $this->hasMany(ClientPreferenceAchat::class)->where('etat', 1);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function commandesEnCours()
    {
        return $this->hasMany(Commande::class)
                    ->with('situation')
                    ->with('detailCommande')
                    ->where('situation_id', '=', '1')
                    ->orWhere('situation_id','=','2');
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }
    
}