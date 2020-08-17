<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandeDetail extends Model
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'quantite_commande' => 'integer',
        'quantite_achat'    => 'integer',
        'prix_u_commande'   => 'double',
        'prix_u_achat'      => 'double',
    ];
    
    /** 
     * Relationship : 
     * 
     * */

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }


}