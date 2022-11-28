<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Orderitems;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json([
            'status' => 200,
            'orders' =>$orders,
        ]);
    }

    public function viewOrder($id)
    {
        $order = Order::find($id);
        if($order)
        {
            return response()->json([
                'status'=>200,
                'order'=>$order
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Commande non trouvée'
            ]);
        }
    }

    public function viewOrderItem($id)
    {
        $orderItem = Orderitems::where('order_id',$id)->get();
        if($orderItem)
        {
            return response()->json([
                'status'=>200,
                'orderItem'=>$orderItem
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Commande non trouvée'
            ]);
        }
    }
}
