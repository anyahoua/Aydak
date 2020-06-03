<?php

namespace App;

use App\Models\UserInfo;
use App\Models\GroupeUser;
use App\Models\Groupe;
use App\Models\InvitationShopper;
use App\Models\DocUser;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'prenom', 'username', 'email', 'password',
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
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /** 
     * Relationship : 
     * 
     * */

    public function userInfo()
    {
        return $this->hasOne(UserInfo::class)->with('Profil');
    }
    
    public function groupeUser()
    {
        return $this->hasOne(GroupeUser::class);
    }

    public function groupe()
    {
        return $this->hasOneThrough(
            Groupe::class, 
            GroupeUser::class,
            'id',
            'id',
            'id',
            'groupe_id'
        );
    }

    public function invitations()
    {
        return $this->hasMany(InvitationShopper::class);
    }

    public function documents()
    {
        return $this->hasMany(DocUser::class);
    }



}
