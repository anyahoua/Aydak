<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $table        = 'commissions';
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
        'valeur', 'profil_id', 'etat', 
    ];


    protected $casts = [
        'valeur' => 'int',
    ];

    public function profil()
    {
        return $this->belongsTo(Profil::class)->where('etat', '1');
    }

/*
    public function produits()
    {
        return $this->hasMany(Produit::class)->where('etat', '1');
    }    

    public function sousCategorieSearch()
    {
        return $this->belongsTo(SousCategorie::class)->where('etat', '1')->with('categorie');
    }
*/
}