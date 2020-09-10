<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClotureCoursier extends Model
{
    protected $table        = 'cloture_coursiers';
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
        'montant_achat', 'pourcentage', 'commission', 'nom_groupe', 'groupe_id', 'user_id', 'nom', 'prenom',  
    ];

}
