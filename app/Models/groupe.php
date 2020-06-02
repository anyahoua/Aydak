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
    public function groupes()
    {
        return $this->belongsToMany(GroupeUser::class);
    }
    */

    public function usersGroupe()
    {
        return $this->hasManyThrough(
            User::class, 
            GroupeUser::class,
            'groupe_id',
            'id',
            'id',
            'user_id'
        );
    }




}
