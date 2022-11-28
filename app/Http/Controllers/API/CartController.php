<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addtocart(Request $request)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_name = $request->product_name;
            $product_qty = $request->product_qty;

            $productCheck = Product::where('id',$product_id)->first();
            if($productCheck)
            {
                if(Cart::where('product_id',$product_id)->where('user_id',$user_id)->exists())
                {
                    return response()->json([
                        'status'=>409,
                        'message'=>$productCheck->name.'Déjà ajouté au panier!',
                    ]);
                }
                else
                {
                    $cartitem = new Cart;
                    $cartitem->user_id = $user_id;
                    $cartitem->product_id = $product_id;
                    $cartitem->product_name = $product_name;
                    $cartitem->product_qty = $product_qty;
                    $cartitem->save();
                    return response()->json([
                        'status'=>201,
                        'message'=>"Produit ajouté au panier avec succès!",
                    ]);
                }
            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'message'=>'Article non trouvé!',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status'=>401,
                'message'=>'Connectez-vous pour ajouter au panier!',
            ]);
        }
    }

    public function viewcart()
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $cartitem = Cart::where('user_id',$user_id)->get();
            return response()->json([
                'status'=>200,
                'cart'=>$cartitem,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>401,
                'message'=>'Connectez-vous pour voir les produits en panier!',
            ]);
        }
    }
    public function updatequantity($cart_id,$scope){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $cartitem = Cart::where('id',$cart_id)->where('user_id',$user_id)->first();
            if($scope == "inc"){
                $cartitem->product_qty +=1;
            }
            else if($scope == "dec")
            {
                $cartitem->product_qty -=1;
            }
            $cartitem->update();
            return response()->json([
                'status'=>200,
                'message'=>'Quantité mise à jour.',
            ]);
        }
        else
        {
            return response()->json([
                'status'=>401,
                'message'=>'Connectez-vous pour continuer!',
            ]);
        }
    }

    public function deletecartitem($cart_id){
     if(auth('sanctum')->check())
     {
        $user_id = auth('sanctum')->user()->id;
        $cartitem = Cart::where('id',$cart_id)->where('user_id',$user_id)->first();
        if($cartitem)
        {
            $cartitem->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Article supprimé du panier avec succès!',
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Article non trouvé!',
            ]);
        }

     }
     else
     {
         return response()->json([
             'status'=>401,
             'message'=>'Connectez-vous pour continuer!',
         ]);
     }
    }
}
