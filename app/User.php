<?php

namespace App;

use App\Models\Pays;
use App\Models\UserAdresse;
use App\Models\UserInfo;
use App\Models\UserCompte;
use App\Models\UserCommande;
use App\Models\Commande;
use App\Models\GroupeUser;
use App\Models\Groupe;
use App\Models\InvitationShopper;
use App\Models\DocUser;
use App\Models\Situation;
use App\Models\UserConnexion;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'prenom', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     * integer, real, float, double, decimal:<digits>, string, boolean, object, array, collection, date, datetime, and timestamp.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /** 
     * Relationship : 
     * 
     * */
    
     public function findForPassport($identifier) {
        return $this->orWhere('email', $identifier)->orWhere('username', $identifier)->first();
    }

    public function orderState()
    {

        return $this->hasManyThrough(
            commande::class, 
            UserCommande::class,
            'user_id',
            'id',
            'id',
            'commande_id'
        )
        //->withCount('situation');
        ->groupBy('situation_id');
        //->where('situation_id', '2');
        
    }


     public function orderState1($state)
    {

        return $this->hasManyThrough(
            commande::class, 
            UserCommande::class,
            'user_id',
            'id',
            'id',
            'commande_id'
        )->where('situation_id', $state);

        //->withCount('situation')
        //->groupBy('situation_id');
        //->where('situation_id', '2');
        
    }


//-----------
    public function ordersUser()
    {
        return $this->hasManyThrough(
            commande::class, 
            UserCommande::class,
            'user_id',
            'id',
            'id',
            'commande_id'
        );
    }

    public function ordersUserTest()
    {
        return $this->hasMany(UserCommande::class);
    }

    public function totalOrdersTm()
    {
        return Commande::where('groupe_id', $this->groupeUser->groupe_id)->get()->count();
    }


    public function userStateCommandesShoppers()
    {
        //--------------------------------------------------------------------------------------------//
        //--------------------------------------------------------------------------------------------//
        /**
         * -x-1 notTreated;
         * -x-2 refused;
         * -x-3 acceptedNotAssigned;
         * 
         * --------------------------
         * 4 assigneeNotPurchased;
         * 5 purchasedUnchecked;
         * 6 checkedNotDelivered;
         * 7 delivered;
         * --------------------------

        */
        //$situations = Situation::all();
        //$situ = array('1','2','3');
        //$situations = Situation::whereIn('id', $situ)->get();
        $situations = Situation::where('type', 2)->get();
        
        $totalCommandes = $this->ordersUser->count();

        
        foreach($situations as $key => $situation)
        {
            //$dataCommandes = Commande::select('situation_id', DB::raw('COUNT(id) as amount'))->where('situation_id', $situation->id)->get();
            $dataCommandes = UserCommande::select('situation_id', DB::raw('COUNT(id) as amount'))
                            ->where('user_id', $this->id)
                            ->where('situation_id', $situation->id)
                            ->get();
            
            foreach($dataCommandes as $i => $dataCommande)
            {
                if(empty($dataCommande->situation_id))
                {
                    $data[$key][$i]['stateName'] = $situation->libely;
                    $data[$key][$i]['type'] = $situation->id;
                    $data[$key][$i]['ratio'] = $totalCommandes ? number_format($dataCommande->amount/$totalCommandes, 3) : 0;
                    $data[$key][$i]['orderCount'] = 0;
                    
                } else {

                    $data[$key][$i]['stateName'] = $situation->libely;
                    $data[$key][$i]['type'] = $dataCommande->situation_id;
                    $data[$key][$i]['ratio'] = $totalCommandes ? number_format($dataCommande->amount/$totalCommandes, 3) : 0;
                    $data[$key][$i]['orderCount'] = $dataCommande->amount;
                    
                }
            }
        }

        $data2 = array_merge($data[0], $data[1], $data[2], $data[3]);//, $data[4], $data[5], $data[6]);
        
        return $data2;
    }

    public function userStateCommandesTeamleader()
    {
        /**
         * --------------------------
         * 1 notTreated;
         * 2 refused;
         * 3 acceptedNotAssigned;
         * --------------------------
         * 
         * -x-4 assigneeNotPurchased;
         * -x-5 purchasedUnchecked;
         * -x-6 checkedNotDelivered;
         * -x-7 delivered;

        */
        $situations = Situation::all();
        //$situ = array('1','2','3');
        //$situations = Situation::whereIn('id', $situ)->get();
        //$situations = Situation::where('type', 1)->get();
        
        $totalCommandes = Commande::where('groupe_id', $this->groupeUser->groupe_id)->get()->count();
        
        foreach($situations as $key => $situation)
        {
            $dataCommandes = UserCommande::select('situation_id', DB::raw('COUNT(id) as amount'))->where('situation_id', $situation->id)->get();
            
            foreach($dataCommandes as $i => $dataCommande)
            {
                if(empty($dataCommande->situation_id))
                {
                    $data[$key][$i]['stateName'] = $situation->libely;
                    $data[$key][$i]['type'] = $situation->id;
                    $data[$key][$i]['ratio'] = $totalCommandes ? number_format($dataCommande->amount/$totalCommandes, 3) : 0;
                    $data[$key][$i]['orderCount'] = 0;
                    
                } else {

                    $data[$key][$i]['stateName'] = $situation->libely;
                    $data[$key][$i]['type'] = $dataCommande->situation_id;
                    $data[$key][$i]['ratio'] = $totalCommandes ? number_format($dataCommande->amount/$totalCommandes, 3) : 0;
                    $data[$key][$i]['orderCount'] = $dataCommande->amount;
                    
                }
            }
        }

        $data2 = array_merge($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
        
        return $data2;
    }


    
    public function orderStateUserLogin()
    {
        //if($this->userInfo->profil_id==1)
        //if($this->userProfil->profil_id==1)
        if($this->userInfo->current_profil_id == 1)
        {
            $data = ['totalOrders' => $this->ordersUser->count(), 'orderState' => $this->userStateCommandesShoppers() ];

        } else {
            $data = ['totalOrders' => $this->totalOrdersTm(), 'orderState' => $this->userStateCommandesTeamleader() ];
        }

        return $data;
    }
    
    public function userLocationAddress()
    {
        return $this->hasOne(UserAdresse::class);
    }

    public function userInformation()
    {
        return $this->hasOne(UserInfo::class);
    }

     public function userInfo()
    {
        return $this->hasOne(UserInfo::class)->with('Profil');
    }
    
    public function coursiers()
    {
        return $this->hasMany(UserInfo::class)->where('profil_id', '2');
    }
    
    public function teamleaders()
    {
        return $this->hasMany(UserInfo::class)->where('profil_id', '1');
    }

    // User Compte
    public function userCompte()
    {
        return $this->hasOne(UserCompte::class)->where('etat','1');
    }

    public function groupeUser()
    {
        return $this->hasOne(GroupeUser::class);
    }

    public function groupeUsers()
    {
        return $this->hasMany(GroupeUser::class);
    }


    public function groupe()
    {
        return $this->hasOneThrough(
            Groupe::class, 
            GroupeUser::class,
            'user_id',
            'id',
            'id',
            'groupe_id'
        );
    }

    public function invitations()
    {
        return $this->hasMany(InvitationShopper::class);
    }

    public function documents()
    {
        return $this->hasMany(DocUser::class);
    }

    /*
    public function userProfil()
    {
        return $this->hasOne(UserConnexion::class)->latest('id');
    }
    */

    public function userWallet()
    {
        //return $this->hasOne(UserCompte::class)->where('etat', '1')->where('profil_id', 1);
        
        // if($this->userProfil->profil_id==1)
        // {
        //     return $this->hasOne(UserCompte::class)->where('etat', '1')->where('profil_id', 2);

        // } else {
        //     return $this->hasOne(UserCompte::class)->where('etat', '1')->where('profil_id', 1);
        // }

        if($this->userInformation->current_profil_id==1)
        {
            return $this->hasOne(UserCompte::class)->where('etat', '1')->where('profil_id', 1);

        } else {
            return $this->hasOne(UserCompte::class)->where('etat', '1')->where('profil_id', 2);
        }

    }


    public function ShopperWallet()
    {
        return $this->hasOne(UserCompte::class)->where('etat', '1')->where('profil_id', 2);
    }

    public function TeamleaderWallet()
    {
        return $this->hasOne(UserCompte::class)->where('etat', '1')->where('profil_id', 1);
    }

    // public function shoppersShoppingByDay()
    // {
    //     return $this->hasOne(UserCompte::class)
    //     ->selectRaw('user_comptes.user_id, user_comptes.profil_id, user_comptes.groupe_id, sum(debit) as total_shopping_by_day')
    //     ->where('debit', '>', 0)
    //     ->where('commande_id', '!=', 0)
    //     ->where('profil_id', 2)
    //     //->whereDate('created_at', Carbon::yesterday()) // Carbon::today(), Carbon::yesterday()
    //     ;
    // }

    public function shoppersTotalPracing()
    {
        $total = $this->hasOne(UserCompte::class)
                ->selectRaw('user_comptes.user_id, sum(debit) as total_shopping_by_day')
                ->where('debit', '>', 0)
                ->where('commande_id', '!=', 0)
                ->where('profil_id', 2)
                ->groupBy('user_id')
                ->whereDate('created_at', Carbon::yesterday()); // Carbon::today(), Carbon::yesterday()

        return $total;
    }

    public function teamleaderTotalPracing()
    {
        return $this->hasOne(UserCompte::class)
        ->selectRaw('user_comptes.user_id, user_comptes.groupe_id, sum(debit) as total_shopping_by_day')
        ->where('debit', '>', 0)
        ->where('commande_id', '!=', 0)
        ->where('groupe_id', 1)
        ->groupBy('user_id')
        //->whereDate('created_at', Carbon::yesterday()) // Carbon::today(), Carbon::yesterday()
        ;
    }



    public function teamleadersShoppingByDay000000()
    {
        return $this->hasOne(UserCompte::class)
        ->selectRaw('user_comptes.user_id, user_comptes.groupe_id, sum(debit) as total_shopping_by_day')
        ->where('debit', '>', 0)
        ->where('commande_id', '>', 0)
        ->where('profil_id', 2)
        ->whereDate('created_at', Carbon::yesterday()) // Carbon::today(), Carbon::yesterday()
        ;
    }
 
}
