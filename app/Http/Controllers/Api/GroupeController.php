<?php

namespace App\Http\Controllers\Api;

use App\Models\Groupe;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

use App\Http\Resources\Api\Clients\GroupesListeRessource;

//class GroupeController extends Controller
class GroupeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Groupes = Groupe::all();

        return $this->successResponse($Groupes, 'Successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'groupeName'      => 'required',  // nom
            'avatar'        => 'required',
            //'latitude'      => 'required | string',
            //'longitude'     => 'required | string', 
            'longitude'     => 'required | numeric',
            'latitude'      => 'required | numeric',
        ]);
        
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        if(Groupe::where('nom', $request->groupeName)->count()==0)
        {
            // Add New Groupe :
            $data = [
                'nom'               => $request->groupeName,
                'photo'             => $request->avatar,
                'latitude'          => $request->latitude,
                'longitude'         => $request->longitude,
                //'deg2rad_longitude' => '',
                //'deg2rad_latitude'  => '',
                'etat'              => '1',
            ];

            $groupe   = Groupe::create($data);

            return response()->json([
                'code'      => '201',
                'message'   => 'Groupe ajouté avec succès.',
                'data'      => $groupe
                
            ], 201);
        }

        return response()->json(['error'=>'Ce nom de groupe existe déjà.'], 401);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $groupe = Groupe::find($id);
        //$groupe->usersInGroupe;
        $groupe->usersInGroupe;
        //$groupe->usersInfo;
        //$groupe->groupes;
        //$groupe->users->userInfo->profil;
        
        return $this->successResponse($groupe, 'Successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /*
    |-------------------------------------------------------------------------------
    | CLIENT        : Listing Groupes
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/clients/groupesList
    | Method        : PUT
    | Description   : Show a listing of groupes by current connected Client.
    |-------------------------------------------------------------------------------
    */
    public function groupeListe(Request $request)
    {
        $Groupes = Groupe::where('etat', 1)
        ->withCount('shoppersInGroupe')
        //->with(['TeamleaderInGroupe', 'shoppersInGroupe'])
        ->with(['TeamleaderInGroupe'])
        ->get();

        return $this->successResponse(GroupesListeRessource::collection($Groupes), 'Successfully');
    }

    /*
    |-------------------------------------------------------------------------------
    | CLIENT        : Find Nearest Groupe
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/clients/findnearest
    | Method        : PUT
    | Description   : Show a listing of groupes (Find Nearest Groupe) by current connected Client.
    |-------------------------------------------------------------------------------
    | @radius       : integer 
    | @latitude     : numeric 
    | @longitude    : numeric
    |-------------------------------------------------------------------------------    
    */
    public function findNearestGroupes(Request $request)
    {
        $latitude   = $request->latitude;
        $longitude  = $request->longitude;
        $radius     = $request->radius;

        $groupes    = Groupe::selectRaw("id, nom, photo, latitude, longitude, etat ,
                        ( 6371 * acos( cos( radians(?) ) *
                        cos( radians( latitude ) )
                        * cos( radians( longitude ) - radians(?)
                        ) + sin( radians(?) ) *
                        sin( radians( latitude ) ) )
                        ) AS distance", [$latitude, $longitude, $latitude])
                        //->Join('groupes', 'groupes.id', '=', 'groupe_users.groupe_id')
            ->where('etat', '=', 1)
            ->with('TeamleaderInGroupe')
            
            //->with('CoursiersInGroupe')
            ->withCount('CoursiersInGroupe')
            
            ->having("distance", "<", $radius)
            ->orderBy("distance",'asc')
            //->offset(0)
            //->limit(20)
            ->get();
        

        return $this->successResponse($groupes, 'Successfully');
    }


}