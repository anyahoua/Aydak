<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Famille extends Model
{

    protected $table        = 'familles';
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
        'sous_categorie_id', 'libely', 'etat', 'icon',
    ];

    public function sousCategorie()
    {
        return $this->belongsTo(SousCategorie::class)->where('etat', '1')->with('categorie');
    }

    public function produits()
    {
        return $this->hasMany(Produit::class)->where('etat', '1');
    }    

    public function sousCategorieSearch()
    {
        return $this->belongsTo(SousCategorie::class)->where('etat', '1')->with('categorie');
    }


}