<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
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

    $token = $user->createToken($user->email.'_Token',[''])->plainTextToken;
    return response()->json([
        'status'=>200,
        'username'=>$user->name,
        'token'=>$token,
        'message'=>'Enregistré avec succès',
    ]);
}
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|max:191',
            'password'=>'required',
        ],
            [
                'email.required'=>'Le champ Adresse email est obligatoire.',
                'email.max'=>'La longueur de l\'adresse e-mail est trop longue. La longueur maximale est de 191',
                'password.required'=>'Le champ Mot de passe est obligatoire.',
            ]);

        if($validator->fails()){
            return response()->json([
                'validation_errors'=>$validator->errors(),
            ]);
        }
        else
        {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
               return response()->json([
                   'status'=>401,
                   'message'=>'Login et mot de passe incorrects, veuillez les
                   vérifier.',
               ]);
            }
            else
            {
                if($user->role_as == 1)
                {
                    $role = 'admin';
                    $token = $user->createToken('_AdminToken',['server:admin'])->plainTextToken;
                }
                else
                {
                    $role = '';
                    $token = $user->createToken($user->email.'_Token',[''])->plainTextToken;
                }
                return response()->json([
                    'status'=>200,
                    'username'=>$user->name,
                    'token'=>$token,
                    'message'=>'Connecté avec succès',
                    'role'=>$role,
                ]);
            }
        }
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>200,
            'message'=>'Déconnecté avec succès.',
        ]);
    }

}
