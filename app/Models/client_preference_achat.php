<?php

namespace App\Models;

use App\Client;
use Illuminate\Database\Eloquent\Model;

class client_preference_achat extends Model
{
    
    protected $table        = 'client_preference_achats';
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
        'quantite_produit', 'etat', 'client_id', 'produit_id', 
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
