<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class commande_detail extends Model
{
    
    protected $table        = 'commande_details';
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
        'quantite_commande', 'quantite_achat', 'prix_u_commande', 'prix_u_achat', 'etat', 'commande_id', 'produit_id', 
    ];

    
    /** 
     * Relationship : 
     * 
     * */




}