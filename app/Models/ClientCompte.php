<?php

namespace App\Models;

use App\Client;
use Illuminate\Database\Eloquent\Model;

class ClientCompte extends Model
{

    protected $table        = 'client_comptes';
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
        'debit', 'credit', 'ancien_solde', 'nouveau_solde', 'etat', 'client_id', 'groupe_id',
    ];

    /** 
     * Relationship : 
     * 
     * */

    public function Client()
    {
        return $this->belongsTo(Client::class);
    }

}
