<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class famille extends Model
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


}
