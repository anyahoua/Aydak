<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table        = 'categories';
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
        'libely', 'etat', 'icon',
    ];

    /** 
     * Relationship : 
     * 
     * */
    public function sousCategories()
    {
        return $this->hasMany(SousCategorie::class)->where('etat', '1');
    }  

     
}
