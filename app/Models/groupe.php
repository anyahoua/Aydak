<?php

namespace App\Models;

use App\User;

use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    
    protected $table        = 'groupes';
    //protected $primaryKey   = 'id';
    //public $incrementing    = false;
    //protected $keyType      = 'string';
    //public $timestamps      = false;
    //protected $dateFormat   = 'U';
    //const CREATED_AT        = 'creation_date';
    //const UPDATED_AT        = 'last_update';
    //protected $connection   = 'connection-name';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'photo', 'latitude', 'longitude', 'deg2rad_longitude', 'deg2rad_latitude', 'etat',
    ];

    
    /** 
     * Relationship : 
     * 
     * */

    /*
        public function users()
        {
            return $this->hasMany(User::class);
        }

        public function invitations()
        {
            return $this->hasMany(InvitationShopper::class);
        }
    */

    public function usersInGroupe()
    {
        return $this->hasManyThrough(
            User::class, 
            GroupeUser::class,
            'groupe_id',
            'id',
            'id',
            'user_id'
        )->with('UserInfo');
    }

    /*
    public function usersInfo()
    {
        return $this->hasManyThrough(
            UserInfo::class, 
            GroupeUser::class,
            'groupe_id',
            'id',
            'id',
            'user_id'
        )->with('profil');
    }

    public function usersInGroupe()
    {
        return $this->belongsToMany(User::class, 'groupe_users', 'groupe_id', 'user_id');
                                    //->withPivot('distance', 'duration', 'duration_secondes');
    }
    */




}
