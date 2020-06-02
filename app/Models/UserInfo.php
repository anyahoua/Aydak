<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table        = 'User_infos';
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
        'mobile', 'latitude', 'longitude', 'deg2rad_longitude', 'deg2rad_latitude', 
        'quartier_livraison', 'ville_livraison', 'daira_livraison', 'wilaya_livraison', 'pays_livraison', 
        'adresse_residence', 'quartier_residence', 'ville_residence', 'daira_residence', 'wilaya_residence', 'pays_residence', 
        'user_id', 'profil_id', 'etat', 'etape', 
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profil()
    {
        return $this->belongsTo(Profil::class);
    }


}
