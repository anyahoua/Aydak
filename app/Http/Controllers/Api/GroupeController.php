<?php

namespace App\Http\Controllers\Api;

use App\Models\Groupe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Groupes = Groupe::all();

        return response()->json([
            'code'      => '200',
            'message'   => 'Successfully.',
            'data'      => $Groupes
            
        ], 200);
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
        
        return $groupe;

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



    /* trouver les restaurants les plus proches
    *
    * @param1 : pass current latitude of the driver
    * @param2 : pass current longitude of the driver
    * @param3 : pass the radius in meter within how much distance you wanted to fiter
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
            ->where('etat', '=', 1)
            ->having("distance", "<", $radius)
            ->orderBy("distance",'asc')
            //->offset(0)
            //->limit(20)
            ->get();

        return response()->json([
            'code'      => '200',
            'message'   => 'Successfully.',
            'total'      => $groupes->count(),
            'data'      => $groupes
            
        ], 200);
    }


}