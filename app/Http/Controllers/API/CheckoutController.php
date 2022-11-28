<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function placeorder(Request $request)
    {
        if(auth('sanctum')->check())
        {
            $validator = Validator::make($request->all(),[
                'name' => 'required|max:191',
                'phone'=>'required|max:191',
                'email' => 'required|max:191',
                'address' => 'required|max:191',
                'city'=>'required|max:191',
                'zipcode'=>'required|max:191',
            ],
        [
            'name.required'=>'Le champ Nom Client est obligatoire.',
            'name.max'=>'La longueur du Nom Client est trop longue. La longueur maximale est de 191.',
            'phone.required'=>'Le champ Prénom est obligatoire.',
            'phone.max'=>'La longueur du Prénom est trop longue. La longueur maximale est de 191.',
            'email.required'=>'Le champ Adresse email est obligatoire.',
            'email.max'=>'La longueur du Adresse email est trop longue. La longueur maximale est de 191.',
            'address.required'=>'Le champ Adresse est obligatoire.',
            'address.max'=>'La longueur du Adresse est trop longue. La longueur maximale est de 191.',
            'city.required'=>'Le champ Ville est obligatoire.',
            'city.max'=>'La longueur du Ville est trop longue. La longueur maximale est de 191.',
            'zipcode.required'=>'Le champ Code postal est obligatoire.',
            'zipcode.max'=>'La longueur du Code postal est trop longue. La longueur maximale est de 191.',
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
                $user_id = auth('sanctum')->user()->id;
                $order = new Order;
                $order->user_id = auth('sanctum')->user()->id;
                $order->name = $request->name;
                $order->phone = $request->phone;
                $order->email = $request->email;
                $order->address = $request->address;
                $order->city = $request->city;
                $order->zipcode = $request->zipcode;

                $order->payment_mode = "COD";
                $order->tracking_no = 'fundaecom'.rand(1111,9999);;
                $order->save();

                $cart = Cart::where('user_id',$user_id)->get();
                $orderitems = [];
                foreach($cart as $item){
                    $orderitems[]=[
                        'product_id'=>$item->product_id,
                        'product_name'=>$item->product_name,
                        'qty'=>$item->product_id,
                        'price'=>$item->product->selling_price,
                    ];
                    $item->product->update([
                        'qty'=>$item->product->qty - $item->product_qty,
                    ]);
                }

                $order->orderitems()->createMany($orderitems);
                Cart::destroy($cart);

                return response()->json([
                    'status'=>200,
                    'message'=>'Commande passée avec succès',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status'=>401,
                'message'=>'Connectez-vous pour continuer',
            ]);
        }
    }

    public function getUser(){
        if(auth('sanctum')->check())
        {
        $user = auth('sanctum')->user();
        return response()->json([
            'status'=>200,
            'user'=>$user,
        ]);
        }
        else
        {
            return response()->json([
                'status'=>401,
                'message'=>'Connectez-vous pour continuer',
            ]);
        }
    }
}
