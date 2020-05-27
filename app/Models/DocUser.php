<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocUser extends Model
{
    
    protected $table        = 'doc_users';
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
        'doc', 'etat', 'user_id', 
    ];

    
    /** 
     * Relationship : 
     * 
     * */

    public function user()
    {
        return $this->belongsTo(User::class);
    }



}
