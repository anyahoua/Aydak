<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCompte extends Model
{
    protected $table        = 'user_comptes';
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
        'debit', 'credit', 'ancien_solde', 'nouveau_solde', 'etat', 'user_id', 'groupe_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
