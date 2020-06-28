<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCommande extends Model
{
    protected $table        = 'user_commandes';
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
        'user_id', 'commande_id', 'client_id', 'etat', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);//->with('detailCommande');
    }

    public function client()
    {
        return $this->belongsTo(Client::class)->with('clientInfo');
    }



}
