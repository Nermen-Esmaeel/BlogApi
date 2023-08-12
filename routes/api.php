<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**************     Admin Routes    *************/
Route::group([

    'prefix' => 'admin'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth.guard:admin-api' , 'jwt.verify' );
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth.guard:admin-api' , 'jwt.verify');
    Route::get('/user-profile', [AuthController::class, 'userProfile'])->middleware('auth.guard:admin-api' , 'jwt.verify');
});


/**************     User Routes    *************/
Route::prefix('user')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});


Route::group([
    'middleware' => ['auth.guard:user-api' ,'jwt.verify' ],
    'prefix' => 'user'
], function ($router) {
    Route::get('/user-profile', [UserController::class, 'userProfile']);

});


