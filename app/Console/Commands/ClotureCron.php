<?php

namespace App\Console\Commands;

use App\User;
use App\Client;
use App\Models\UserCompte;
use App\Models\Commission;
use App\Models\ClotureCoursier;
use App\Models\ClotureTeamleader;
use App\Models\ClotureGenerale;
use App\Models\Groupe;

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
        //$dateDay                = Carbon::yesterday();
        $dateDay                = Carbon::today();
        $pourcentageShopper     = Commission::find(3);
        $pourcentageTeamleader  = Commission::find(2);
        $pourcentageAydak       = Commission::find(1);

        //---------------------------------------------------------------------------- 1
        // shoppers : total shopping in yesterday
        //---------------------------------------------------------------------------- 1
        $shoppers           = User::select('id', 'nom', 'prenom')->addSelect(['totalPracing' => UserCompte::selectRaw('sum(debit) as total')
                            ->whereColumn('user_id', 'users.id')
                            ->where('profil_id', 2)
                            ->where('debit', '>', 0)
                            ->where('commande_id', '!=', 0)
                            ->whereDate('created_at', $dateDay)
                            ->groupBy('user_id')
                            ])
                            //->with('groupe')

                            ->with(['groupe' => function ($q) {
                                $q->select('groupes.id', 'groupes.nom', 'groupes.latitude', 'groupes.longitude', 'groupes.daira', 'groupes.photo');
                            }])
                            //->orderBy('totalPracing', 'DESC')
                            ->get();
        //return $shoppers;

        foreach($shoppers as $key => $shopper )
        {
            $montant_achat  = $shopper->totalPracing ? $shopper->totalPracing : 0;

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

        $clotureShoopers    = ClotureCoursier::insert($data);
        //---------------------------------------------------------------------------- 1

        //---------------------------------------------------------------------------- 2
        // Teamleader : total shopping in yesterday
        //---------------------------------------------------------------------------- 2
        $teamleaders = Groupe::select('groupes.id','groupes.nom', 'groupes.photo', 'groupes.daira', 'groupes.latitude', 'groupes.longitude')
        ->addSelect(['totalPracing' => UserCompte::selectRaw('sum(debit) as total')
        ->whereColumn('groupe_id', 'groupes.id')
        ->where('debit', '>', 0)
        ->where('commande_id', '!=', 0)
        ->whereDate('created_at', $dateDay)
        ->groupBy('groupe_id')
        ])
        ->with(['TeamleaderInGroupeSimple' => function ($q) {
            $q->select('users.id', 'users.nom', 'users.prenom');
        }])

        //->orderBy('totalPracing', 'DESC')
        ->get();
        
        // return $teamleaders;

        foreach($teamleaders as $key => $teamleader )
        {
            $montant_achat = $teamleader->totalPracing ? $teamleader->totalPracing : 0;

            $data[$key]['montant_achat']  = $montant_achat;
            $data[$key]['pourcentage']    = $pourcentageTeamleader->valeur;
            $data[$key]{'commission'}     = $montant_achat*($pourcentageTeamleader->valeur/100);
            $data[$key]['nom_groupe']     = $teamleader->nom;
            $data[$key]['groupe_id']      = $teamleader->id;
            $data[$key]['user_id']        = $teamleader->TeamleaderInGroupeSimple['id'];
            $data[$key]['nom']            = $teamleader->TeamleaderInGroupeSimple['nom'];
            $data[$key]['prenom']         = $teamleader->TeamleaderInGroupeSimple['prenom'];
            $data[$key]['created_at']     = Carbon::now();
            $data[$key]['updated_at']     = Carbon::now();
        }

        // return $data;

        $clotureTeamleader      = ClotureTeamleader::insert($data);
        //---------------------------------------------------------------------------- 2

        //---------------------------------------------------------------------------- 3
        // Aydak : total shopping in yesterday
        //---------------------------------------------------------------------------- 3
        $aydak                  = UserCompte::where('debit', '>', 0)
                                ->where('commande_id', '!=', 0)
                                ->whereDate('created_at', $dateDay)
                                ->sum('debit');
        
        $montant_achat          = $aydak;
        
        $data['montant_achat']  = $montant_achat;
        $data['pourcentage']    = $pourcentageAydak->valeur;
        $data['commission']     = $montant_achat*($pourcentageAydak->valeur/100);
        
        //return $data;
        
        $clotureGenerale                = new ClotureGenerale;
        
        $clotureGenerale->montant_achat = $data['montant_achat'];
        $clotureGenerale->pourcentage   = $data['pourcentage'];
        $clotureGenerale->commission    = $data['commission'];
        
        $clotureGenerale        = $clotureGenerale->save();
        //---------------------------------------------------------------------------- 3

        return true;
    }
}
