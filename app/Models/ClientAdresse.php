<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAdresse extends Model
{
    protected $table        = 'client_adresses';
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
        'latitude', 'longitude', 'quartier', 'commune', 'daira', 'wilaya', 'pays_id', 'client_id', 'etat', 
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }


}