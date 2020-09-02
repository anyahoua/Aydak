<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandeCommentaire extends Model
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
    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function profil()
    {
        return $this->belongsTo(Profil::class);
    }

    public function teamleader()
    {
        return $this->belongsTo(Profil::class)->where('profil_id', 1);
    }

    public function shopper()
    {
        return $this->belongsTo(Profil::class)->where('profil_id', 2);
    }
    
    public function client()
    {
        return $this->belongsTo(Profil::class)->where('profil_id', 3);
    }


}