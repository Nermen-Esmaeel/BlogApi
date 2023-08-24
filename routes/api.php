<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\PostTagController;
use App\Http\Controllers\Api\CategoryController;


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





//  Auth Routes
Route::prefix('user')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/user-profile', [UserController::class, 'userProfile'])->middleware( 'jwt.verify');
    Route::post('/logout', [UserController::class, 'logout'])->middleware('jwt.verify' );
    Route::post('/refresh', [UserController::class, 'refresh'])->middleware('jwt.verify');
    Route::post('/update-profile/{id}', [UserController::class, 'updateProfile'])->middleware('jwt.verify');
    Route::post('/change-plan/{id}', [UserController::class, 'changePlan'])->middleware('jwt.verify' , 'isAdmin');
});


Route::group([ 'middleware' => 'jwt.verify' ,'prefix' => 'user'],
    function() {

        //Categories Routes  
        Route::get('/categories', [CategoryController::class, 'index'])->middleware('isAdmin');
        Route::get('/category/{id}' , [CategoryController::class , 'show']);
        Route::post('/categories' , [CategoryController::class , 'store'])->middleware('isAdmin');
        Route::post('/categories/{id}' , [CategoryController::class , 'update'])->middleware('isAdmin');
        Route::post('/category/{id}' , [CategoryController::class , 'destroy'])->middleware('isAdmin');

        //Tags Routes  
        Route::get('/tags', [TagController::class, 'index']);
        Route::get('/tag/{id}' , [TagController::class , 'show']);
        Route::post('/tags' , [TagController::class , 'store'])->middleware('isAdmin');
        Route::post('/tags/{id}' , [TagController::class , 'update'])->middleware('isAdmin');
        Route::post('/tag/{id}' , [TagController::class , 'destroy'])->middleware('isAdmin');

         //Post Routes  
         Route::get('/posts', [PostController::class, 'index'])->middleware( 'checkPlan');
         Route::get('/post/{id}' , [PostController::class , 'show']);
         Route::post('/posts' , [PostController::class , 'store'])->middleware('isAdmin');
         Route::post('/posts/{id}' , [PostController::class , 'update'])->middleware('isAdmin');
         Route::post('/post/{id}' , [PostController::class , 'destroy'])->middleware('isAdmin');

          //Post Tag Routes
          Route::post('/post.tags/{id}' , [PostTagController::class , 'addTagsForPost']);
          Route::post('/post.tag/{id}' , [PostTagController::class , 'deleteTagForPost']);
          Route::get('/post.tags/{id}' , [PostTagController::class , 'show']);



        //Image Routes
         Route::get('/images', [ImageController::class, 'index']);
         Route::get('/image/{id}' , [ImageController::class , 'show']);
         Route::post('/images' , [ImageController::class , 'store']);
         Route::post('/images/{id}' , [ImageController::class , 'update']);
         Route::post('/image/{id}' , [ImageController::class , 'destroy']);

         Route::get('/posts/search/{name}' , [PostController::class , 'search']);

});


