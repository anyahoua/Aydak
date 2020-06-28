<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    
    protected $table        = 'produits';
    //protected $primaryKey   = 'id';
    //public $incrementing    = false;
    //protected $keyType      = 'string';
    public $timestamps      = false;
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
        'libely', 'commentaire', 'unite_val', 'famille_id', 'unite_mesure_id', 'etat', 'photo',
    ];

    
    /** 
     * Relationship : 
     * 
     * */
    public function prix()
    {
        return $this->hasOne(ProduitPrix::class)->where('etat', '1');
    }

    public function historyPrice()
    {
        return $this->hasMany(ProduitPrix::class);
    }






}
