<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('layouts.mainlayout');
// });

Route::get('/', function () {
    return view('auth.login');
});

// Route::get('login', function () {
//     return view('login');
// });

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/register', [App\Http\Controllers\HomeController::class, 'view'])->name('register');

Route::group(['middleware' => ['auth'] ], function() {

    // User Roles
    Route::get('role/list', [RoleController::class,'index']);
    Route::get('role/loaddata', [RoleController::class,'loadData'])->name("rolesdata");
    Route::get('role/create', [RoleController::class,'create']);
    Route::post('role/save', [RoleController::class,'store']);
    Route::get('role/edit/{id}', [RoleController::class,'edit']);
    Route::post('role/update', [RoleController::class,'update']);

    Route::get( 'feature', function( ){
        $data['menu'] = '';
        return view('empty', $data);
    });

});


