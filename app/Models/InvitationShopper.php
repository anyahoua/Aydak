<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationShopper extends Model
{
    
    protected $table        = 'invitation_shoppers';
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
        'mobile', 'code', 'date_envoie', 'date_activation', 'etat', 'user_id', 'groupe_id', 
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
