<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientInfo extends Model
{
    protected $table        = 'client_infos';
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
    /*protected $fillable = [
        'mobile', 'quartier', 'latitude', 'longitude', 'deg2rad_longitude', 'deg2rad_latitude', 'ville', 'daira', 'wilaya', 'pays', 'client_id', 'etat', 
    ];*/
    protected $fillable = [
        'mobile', 'client_id', 'etat', 
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
}
