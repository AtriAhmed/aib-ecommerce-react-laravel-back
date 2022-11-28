<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RepairController;
use App\Http\Controllers\API\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register',[AuthController::class, 'register']);
Route::post('login',[AuthController::class, 'login']);

Route::get('search/{keyword}',[ProductController::class, 'search']);

Route::get('get-user',[CheckoutController::class,'getUser']);
Route::get('get-bdtypes',[RepairController::class,'getBDT']);
Route::post('create-bdsheet',[RepairController::class,'createBDS']);
Route::get('get-user-devis',[RepairController::class,'getUserDevis']);
Route::get('getCategory',[FrontendController::class,'category']);
Route::get('fetchproducts/{slug}',[FrontendController::class,'product']);
Route::get('viewproductdetails/{category_slug}/{product_slug}',[FrontendController::class,'viewproduct']);
Route::get('latest-products',[ProductController::class,'latests']);
Route::get('biggest-discounts',[ProductController::class,'biggestdiscounts']);
Route::post('add-to-cart',[CartController::class,'addtocart']);
Route::get('cart',[CartController::class, 'viewcart']);
Route::put('cart-updatequantity/{cart_id}/{scope}',[CartController::class, 'updatequantity']);
Route::delete('delete-cartitem/{cart_id}',[CartController::class, 'deletecartitem']);

Route::post('place-order',[CheckoutController::class,'placeorder']);

Route::middleware(['auth:sanctum','isAPIAdmin'])->group(function () {

    Route::get('checkingAuthenticated', function(){
        return response()->json(['message'=>'You are in', 'status'=>200], 200);
    });

    Route::get('view-category',[CategoryController::class,'index']);
    Route::post('store-category',[CategoryController::class,'store']);
    Route::get('edit-category/{id}',[CategoryController::class,'edit']);
    Route::put('update-category/{id}',[CategoryController::class,'update']);
    Route::delete('delete-category/{id}',[CategoryController::class,'destroy']);
    Route::get('all-category',[CategoryController::class,'allcategory']);

    Route::post('add-bdtype',[RepairController::class,'createBDT']);
    Route::get('edit-bdtype/{id}',[RepairController::class,'edit']);
    Route::put('update-bdtype/{id}',[RepairController::class,'update']);
    Route::delete('delete-bdtype/{id}',[RepairController::class,'destroy']);

    Route::get('get-bdsheets',[RepairController::class,'getBDS']);
    Route::get('get-bds/{id}',[RepairController::class,'getOneBDS']);

    Route::post('create-devi',[RepairController::class,'createDevi']);
    Route::get('get-devis',[RepairController::class,'getDevis']);
    Route::put('confirm-devi/{id}',[RepairController::class,'confirmDevi']);

    Route::get('admin/orders',[OrderController::class,'index']);
    Route::get('admin/view-order/{id}',[OrderController::class,'viewOrder']);
    Route::get('admin/view-order-item/{id}',[OrderController::class,'viewOrderItem']);

    Route::get('view-product',[ProductController::class,'index']);
    Route::post('store-product',[ProductController::class,'store']);
    Route::get('edit-product/{id}',[ProductController::class,'edit']);
    Route::post('update-product/{id}',[ProductController::class,'update']);
    Route::delete('delete-product/{id}',[ProductController::class,'destroy']);

    Route::get('view-users',[UsersController::class,'index']);
    Route::post('add-user',[UsersController::class,'add']);
    Route::get('edit-user/{id}',[UsersController::class,'edit']);
    Route::put('update-user/{id}',[UsersController::class,'update']);
    Route::delete('delete-user/{id}',[UsersController::class,'destroy']);

});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('logout',[AuthController::class, 'logout']);
});
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
