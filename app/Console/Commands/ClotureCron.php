<?php

namespace App\Console\Commands;

use App\User;
use App\Client;
use App\Models\ClientCompte;
use App\Models\UserCompte;
use App\Models\Commande;
use App\Models\Commission;

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

/*
        \Log::info("Cron is working fine!");
        $this->info('Demo:Cron Cummand Run successfully!');
*/
        $pourcentageTotal       = Commission::sum('valeur');

        //--
        $pourcentageAydak       = Commission::find(1);
        $pourcentageTeamleader  = Commission::find(2);
        $pourcentageShopper     = Commission::find(3);

        //--
        $comptes_shoppers       = UserCompte::where('profil_id', 2)->whereDate('created_at', Carbon::today())->get();
        $comptes_teamleaders    = UserCompte::where('profil_id', 1)->whereDate('created_at', Carbon::today())->get();


    }
}
