<?php

namespace App\Models;
use App\User;

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
        'debit', 'credit', 'ancien_solde', 'nouveau_solde', 'etat', 'user_id', 'profil_id', 'groupe_id', 'type', 'commande_id', 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     * integer, real, float, double, decimal:<digits>, string, boolean, object, array, collection, date, datetime, and timestamp.
     */
    
    protected $casts = [
        'debit'         => 'decimal:2',
        'credit'        => 'decimal:2',
        'ancien_solde'  => 'decimal:2',
        'nouveau_solde' => 'decimal:2'
    ];
    


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profil()
    {
        return $this->belongsTo(Profil::class);
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }


}
