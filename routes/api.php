<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Admin\AuthController;

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
Route::group(['prefix' => 'admin'], function($router) {
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


Route::group(['prefix' => 'user'], function($router) {
    Route::get('/user-profile', [UserController::class, 'userProfile'])->middleware('auth.guard:admin-api' , 'jwt.verify');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth.guard:user-api' , 'jwt.verify');

});


Route::group(['middleware' =>['jwt.verify' , 'auth.guard:user-api'],
    'prefix' => 'user'],
    function($router) {

        /**************     Categories Routes    *************/
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/category/{id}' , [CategoryController::class , 'show']);
        Route::post('/categories' , [CategoryController::class , 'store']);
        Route::post('/categories/{id}' , [CategoryController::class , 'update']);
        Route::post('/category/{id}' , [CategoryController::class , 'destroy']);

        /**************     Tags Routes    *************/
        Route::get('/tags', [TagController::class, 'index']);
        Route::get('/tag/{id}' , [TagController::class , 'show']);
        Route::post('/tags' , [TagController::class , 'store']);
        Route::post('/tags/{id}' , [TagController::class , 'update']);
        Route::post('/tag/{id}' , [TagController::class , 'destroy']);


});
Route::get('tags.posts',[TagController::class ,'tagPosts' ]);



