<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitPrix extends Model
{
    protected $table        = 'produit_prix';
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
        'produit_id', 'prix', 'etat', 
    ];

    
    /** 
     * Relationship : 
     * 
     * */

    public function produit()
    {
        return $this->hasOne(Produit::class);
    }


}