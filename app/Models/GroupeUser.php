<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupeUser extends Model
{
    
    protected $table        = 'groupe_users';
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
        'date_annulation', 'etat', 'user_id', 'groupe_id', 
    ];

    
    /** 
     * Relationship : 
     * 
     * */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }
    
     
}