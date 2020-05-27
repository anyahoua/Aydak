<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class commande_commentaire extends Model
{
    
    protected $table        = 'commande_commentaires';
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
        'nom', 'commentaire', 'etat', 'user_id', 'groupe_id', 'commande_id', 'profil_id', 
    ];

    
    /** 
     * Relationship : 
     * 
     * */




}