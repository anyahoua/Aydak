<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    
    protected $table        = 'commandes';
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
        'date_livraison', 'date_livraison_prevu', 'situation_id', 'client_id', 'groupe_id',
    ];

    
    /** 
     * Relationship : 
     * 
     * */

    public function Client()
    {
        return $this->belongsTo(Client::class);
    }


}