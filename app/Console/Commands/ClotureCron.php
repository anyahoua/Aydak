<?php

namespace App\Console\Commands;

use App\User;
use App\Client;
use App\Models\ClientCompte;
use App\Models\UserCompte;
use App\Models\Commande;
use App\Models\Commission;
use App\Models\ClotureCoursier;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class ClotureCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloture:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cloture des comptes de toute la journée.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        //----------------------------------------------------------------------------
        // Shoppers Shopping By Day
        //----------------------------------------------------------------------------
        $pourcentageShopper = Commission::find(3);
        $shoppers           = User::select('id', 'nom', 'prenom')
                            ->with('shoppersTotalPracing')
                            ->with(['groupe' => function($q) {
                                $q->select('groupes.id','groupes.nom', 'groupes.photo', 'groupes.daira', 'groupes.latitude', 'groupes.longitude');
                            }])
                            ->get();

        foreach($shoppers as $key => $shopper )
        {
            $montant_achat = $shopper->shoppersTotalPracing['total_shopping_by_day'] ? $shopper->shoppersTotalPracing['total_shopping_by_day'] : 0;

            $data[$key]['montant_achat']  = $montant_achat;
            $data[$key]['pourcentage']    = $pourcentageShopper->valeur;
            $data[$key]{'commission'}     = $montant_achat*($pourcentageShopper->valeur/100);
            $data[$key]['nom_groupe']     = $shopper->groupe['nom'];
            $data[$key]['groupe_id']      = $shopper->groupe['id'];
            $data[$key]['user_id']        = $shopper->id;
            $data[$key]['nom']            = $shopper->nom;
            $data[$key]['prenom']         = $shopper->prenom;
            $data[$key]['created_at']     = Carbon::now();
            $data[$key]['updated_at']     = Carbon::now();
        }

        //return $data;
        return ClotureCoursier::insert($data);
        //return $shoppers;


    }
}
