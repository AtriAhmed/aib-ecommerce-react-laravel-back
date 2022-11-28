<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'status'=>200,
            'users'=>$users,
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if($user)
        {
            $user->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Utilisateur supprimée avec succès',
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Utilisateur non trouvé!',
            ]);
        }
    }

    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'name'=>'required|max:191',
            'email'=>'required|email|max:191|unique:users,email',
            'password'=>'required|min:8',
            'tel'=>'required|digits:8',
        ],[
            'name.required'=>'Le champ Nom est obligatoire.',
            'name.max'=>'La longueur du nom est trop longue. La longueur maximale est de 191.',
            'email.required'=>'Le champ Adresse email est obligatoire.',
            'email.email'=>'Le format de l\'adresse e-mail n\'est pas valide',
            'email.max'=>'La longueur de l\'adresse e-mail est trop longue. La longueur maximale est de 191',
            'email.unique'=>'Cette adresse email est déjà utilisée',
            'password.required'=>'Le champ Mot de passe est obligatoire.',
            'password.min'=>'La longueur du mot de passe doit être de 8 caractères ou plus',
            'tel.required'=>'Le champ Tél. est Obligatoire.',
            'tel.digits'=>'La longueur de Numero de Téléphone doit étre 8 nombres.',
        ]);

    if($validator->fails()){
    return response()->json([
        'validation_errors'=>$validator->errors(),
    ]);
    }else{
        $user = User::create([
            'name'=>$request->name,
            'email' =>$request->email,
            'password'=>Hash::make($request->password),
            'tel'=>$request->tel,
        ]);

    return response()->json([
        'status'=>200,
        'username'=>$user->name,
        'message'=>'Enregistré avec succès',
    ]);
}
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name'=>'required|max:191',
            'email'=>'required|email|max:191',
            'tel'=>'required|digits:8',
        ],
        [
            'name.required'=>'Le champ Nom est obligatoire.',
            'name.max'=>'La longueur du nom est trop longue. La longueur maximale est de 191.',
            'email.required'=>'Le champ Adresse email est obligatoire.',
            'email.email'=>'Le format de l\'adresse e-mail n\'est pas valide',
            'email.max'=>'La longueur de l\'adresse e-mail est trop longue. La longueur maximale est de 191',
            'email.unique'=>'Cette adresse email est déjà utilisée',
            'tel.required'=>'Le champ Tél. est Obligatoire.',
            'tel.digits'=>'La longueur de Numero de Téléphone doit étre 8 nombres.',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>422,
                'errors'=>$validator->getMessageBag(),
            ]);
        }
        else
        {
            $user = User::find($id);
            if($user)
            {
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                if($request->input('password')){
                    $user->password = Hash::make($request->input('password'));
                }
                $user->tel = $request->input('tel');
                $user->save();
                return response()->json([
                    'status'=>200,
                    'message'=>'Utilisateur mise à jour avec succès',
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'message'=>'Utilisateur non trouvé!'
                ]);
            }
        }
    }

    public function edit($id)
{
    $user = User::find($id);
    if($user)
    {
        return response()->json([
            'status'=>200,
            'user'=>$user
        ]);
    }
    else
    {
        return response()->json([
            'status'=>404,
            'message'=>'Utilisateur non trouvé!'
        ]);
    }
}
}
