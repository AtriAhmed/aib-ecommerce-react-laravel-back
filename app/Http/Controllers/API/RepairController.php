<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BDS;
use App\Models\BDT;
use App\Models\Devi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RepairController extends Controller
{


public function getBDT()
{
    $bdt = BDT::all();
    return response()->json([
        'status'=>200,
        'bdtypes'=>$bdt,
    ]);
}

public function createBDS(Request $request){

    if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;

    $validator = Validator::make($request->all(),[
        'numS'=>'required|max:191',
        'descrip'=>'required',
    ],
    [
        'numS.required'=>'Le champ Num serie est obligatoire.',
        'numS.max'=>'La longueur du  Num serie est trop longue. La longueur maximale est de 191.',
        'descrip.required'=>'Le champ Description panne est obligatoire.',
    ]);
    if($validator->fails()){
        return response()->json([
            'status'=>400,
            'errors'=>$validator->getMessageBag(),
        ]);
    }
    else
    {
        $bds = new BDS;
        $bds->user_id = $user_id;
        $bds->numS = $request->input('numS');
        $bds->descrip = $request->input('descrip');
        $bds->save();
        return response()->json([
            'status'=>200,
            'message'=>'Fiche panne créer avec succès',
        ]);
    }
}
else
        {
            return response()->json([
                'status'=>401,
                'message'=>'Connectez-vous pour créer une fiche panne!',
            ]);
        }
}

public function createBDT(Request $request){
    $validator = Validator::make($request->all(),[
        'type'=>'required|max:191',
        'descrip'=>'required|max:191',
        'costs'=>'required|max:191',
    ],
    [
        'type.required'=>'Le champ Type est obligatoire.',
        'type.max'=>'La longueur du Type est trop longue. La longueur maximale est de 191.',
        'descrip.required'=>'Le champ Description est obligatoire.',
        'descrip.max'=>'La longueur du Description est trop longue. La longueur maximale est de 191.',
        'costs.required'=>'Le champ Frais est obligatoire.',
        'costs.max'=>'La longueur du Frais est trop longue. La longueur maximale est de 191.',
    ]);
    if($validator->fails()){
        return response()->json([
            'status'=>400,
            'errors'=>$validator->getMessageBag(),
        ]);
    }
    else
    {
        $bdt = new BDT;
        $bdt->type = $request->input('type');
        $bdt->descrip = $request->input('descrip');
        $bdt->costs = $request->input('costs');
        $bdt->save();
        return response()->json([
            'status'=>200,
            'message'=>'Type Panne ajoutée avec succès',
        ]);
    }
}

public function destroy($id)
    {
        $bdt = BDT::find($id);
        if($bdt)
        {
            $bdt->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Type panne supprimée avec succès',
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Type panne non trouvé!',
            ]);
        }
    }

    public function getBDS()
{
    $bds = BDS::all();
    return response()->json([
        'status'=>200,
        'bdsheets'=>$bds,
    ]);
}


public function getOneBDS($id)
{
    $bds = BDS::find($id);
    if($bds)
    {
        return response()->json([
            'status'=>200,
            'bdsheet'=>$bds
        ]);
    }
    else
    {
        return response()->json([
            'status'=>404,
            'message'=>'Fiche panne non trouvé!'
        ]);
    }
}

public function createDevi(Request $request){


    $validator = Validator::make($request->all(),[
        'bds_id'=>'required|max:191',
        'user_id'=>'required|max:191',
        'numS'=>'required|max:191',
        'descrip'=>'required',
        'costs'=>'required',
    ],
    [
        'numS.required'=>'Le champ Num serie est obligatoire.',
        'numS.max'=>'La longueur du  Num serie est trop longue. La longueur maximale est de 191.',
        'descrip.required'=>'Le champ Description panne est obligatoire.',
    ]);
    if($validator->fails()){
        return response()->json([
            'status'=>400,
            'errors'=>$validator->getMessageBag(),
        ]);
    }
    else
    {
        $devi = new Devi;
        $devi->bds_id = $request->input('bds_id');
        $devi->user_id = $request->input('user_id');
        $devi->numS = $request->input('numS');
        $devi->descrip = $request->input('descrip');
        $devi->costs = $request->input('costs');
        $devi->save();
        return response()->json([
            'status'=>200,
            'message'=>'Devi créé avec succès',
        ]);
    }

}

public function getUserDevis()
{
    if(auth('sanctum')->check()){
        $id = auth('sanctum')->user()->id;
        $devis = Devi::where('user_id',$id)->where('status','0')->get();
        if($devis)
        {
            return response()->json([
                'status'=>200,
                'devis'=>$devis,
                ]);
        }
        else
        {
            return response()->json([
                'status'=>400,
                'message'=>'Aucun devis disponible'
            ]);
        }
    }else
    {
        return response()->json([
            'status'=>401,
            'message'=>'Connectez-vous pour continuer',
        ]);
    }
}

public function getDevis()
{
    $devis = Devi::all();
    return response()->json([
        'status'=>200,
        'devis'=>$devis,
    ]);
}

public function confirmDevi($id){

        $devi = Devi::find($id);
        if($devi)
        {
            $devi->status = '1';
            $devi->save();
            return response()->json([
                'status'=>200,
                'message'=>'Devi confirmé avec succès',
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Devi non trouvé!'
            ]);
        }

}
}
