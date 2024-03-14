<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VisitsController;
use App\Http\Controllers\WarehouseProductsController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('v1/login', [AuthController::class, 'login']);
Route::post('v1/register', [AuthController::class, 'register']);
Route::group([
    // // 'middleware' => ['jwt.verify'],
    'prefix' => 'v1'
], function ($router) {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

    Route::post('/roleslist',[RoleController::class,'index']);
    Route::get('/role-create',[RoleController::class,'create']);
    Route::post('/role-store',[RoleController::class,'store']);
    Route::put('/role-show/{id}',[RoleController::class,'show']);
    Route::post('/role-update/{id}',[RoleController::class,'update']);
    Route::post('/role/delete',[RoleController::class,'delete']);
    Route::get('/permissions/{id}',[RoleController::class,'getPermissionsByRoleId']);


    Route::post('/userslist',[UsersController::class,'index']);
    Route::post('/user-store',[UsersController::class,'store']);
    Route::put('/user-show/{id}',[UsersController::class,'show']);
    Route::post('/user-update/{id}',[UsersController::class,'update']);
    Route::post('/user/delete',[UsersController::class, 'delete']);

    Route::post('/customerslist',[CustomerController::class,'index']);
    Route::get('/customer-create',[CustomerController::class,'create']);
    Route::post('/customer-store',[CustomerController::class,'store']);
    Route::put('/customer-show/{id}',[CustomerController::class,'show']);
    Route::post('/customer-update/{id}',[CustomerController::class,'update']);
    Route::post('/customer/delete',[CustomerController::class, 'delete']);
    Route::post('/customer-overview',[CustomerController::class, 'overview']);
    Route::post('/customer-visits',[CustomerController::class,'customerVisits']);
    Route::get('/states/{countryId}', [CustomerController::class,'getStates']);
    Route::get('/cities/{stateId}', [CustomerController::class,'getCities']);

    Route::post('/vendorlist',[VendorController::class,'index']);
    Route::get('/vendor-create',[VendorController::class,'create']);
    Route::post('/vendor-store',[VendorController::class,'store']);
    Route::put('/vendor-show/{id}',[VendorController::class,'show']);
    Route::post('/vendor-update/{id}',[VendorController::class,'update']);
    Route::post('/vendor/delete',[VendorController::class, 'delete']);
    Route::post('/vendor-overview',[VendorController::class, 'overview']);
    Route::post('/vendor-visits',[VendorController::class,'vendorsVisits']);
    Route::get('/states/{countryId}', [VendorController::class,'getStates']);
    Route::get('/cities/{stateId}', [VendorController::class,'getCities']);

    Route::post('/productslist',[ProductsController::class,'index']);
    Route::get('/product-create',[ProductsController::class,'create']);
    Route::post('/product-store',[ProductsController::class,'store']);
    Route::put('/product-show/{id}',[ProductsController::class,'show']);
    Route::post('/product-update/{id}',[ProductsController::class,'update']);
    Route::post('/product/delete',[ProductsController::class, 'delete']);
    Route::get('/customer-order',[ProductsController::class, 'getCustomerOrder']);

    Route::post('/visitslist',[VisitsController::class,'index']);
    Route::put('/visit-show/{id}',[VisitsController::class,'show']);

    Route::post('/prescriptionslist',[PrescriptionController::class,'index']);
    Route::get('/prescription-create',[PrescriptionController::class,'create']);
    Route::post('/prescription-store',[PrescriptionController::class,'store']);
    Route::post('/prescription-attachments',[PrescriptionController::class,'uploadAttachments']);

    // Route::post('/prescription/createLens', 'PrescriptionController@createLens');
    // Route::get('/createLens',[PrescriptionController::class,'addLens']);
    // Route::post('/storeLens', [PrescriptionController::class, 'storeLens']);


    Route::post('/cartslist',[CartController::class,'index']);
    Route::get('/cart-create',[CartController::class,'create']);
    Route::post('/cart-store',[CartController::class,'store']);
    Route::put('/cart-show/{id}',[CartController::class,'show']);
    Route::post('/cart-update/{id}',[CartController::class,'update']);
    Route::post('/cart/delete',[CartController::class, 'delete']);

    Route::post('/search-lens', [CartController::class, 'searchLensMaster']);
    Route::get('/getLensMasterById', [CartController::class, 'getLensMasterById']);



    Route::post('/lenscart-store',[CartController::class,'addLens']);
    Route::post('/lensmeasurementscart-store',[CartController::class,'addMeasurements']);

    Route::post('/orderslist',[OrderController::class,'index']);
    Route::post('/order-store',[OrderController::class,'store']);
    Route::put('/order-show/{id}',[OrderController::class,'show']);
    Route::post('/order-update/{id}',[OrderController::class,'update']);
    Route::post('/CustomerOrdersList',[OrderController::class,'getOrders']);


    Route::post('/inventoryslist',[InventoryController::class,'index']);
    Route::get('/inventory-create',[InventoryController::class,'create']);
    Route::post('/inventory-store',[InventoryController::class,'store']);
    Route::put('/inventory-show/{id}',[InventoryController::class,'show']);
    Route::post('/inventory-update/{id}',[InventoryController::class,'update']);
    Route::post('/inventory/delete',[InventoryController::class, 'delete']);

    Route::post('/brandlist',[BrandController::class,'index']);
    Route::get('/brand-create',[BrandController::class,'create']);
    Route::post('/brand-store',[BrandController::class,'store']);
    Route::put('/brand-show/{id}',[BrandController::class,'show']);
    Route::post('/brand-update/{id}',[BrandController::class,'update']);
    Route::post('/brand-delete',[BrandController::class, 'delete']);

    Route::post('/storeslist',[StoreController::class,'index']);
    Route::get('/store-create',[StoreController::class,'create']);
    Route::post('/store-store',[StoreController::class,'store']);
    Route::put('/store-show/{id}',[StoreController::class,'show']);
    Route::post('/store-update/{id}',[StoreController::class,'update']);
    Route::post('/store/delete',[StoreController::class, 'delete']);

    Route::post('/warehouseproductslist',[WarehouseProductsController::class,'index']);

    Route::get('/dashboard',[DashboardController::class,'index']);

    Route::put('cancel/{orderId}',[OrderController::class,'cancel']);

    Route::get('generate-salesorders-pdf/{orderId}', [OrderController::class, 'generateSaleOrderPDF']);
    Route::get('generate-salesinvoice-pdf/{orderId}', [OrderController::class, 'generateSaleInvoicePDF']);


});
