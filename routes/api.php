<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GitHubController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;

//public routes
Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::get('/recipes', [RecipeController::class,'index']);
Route::get('/categories', [CategoryController::class,'index']);
Route::get('/checkout', [StripeController::class,'index']);

Route::get('/redirect', [GitHubController::class,'redirect']);
Route::get('/callback', [GitHubController::class,'callback']);






//protected routes


Route::group(['middleware'=> ['auth:sanctum']],function(){
Route::post('/logout',[AuthController::class,'logout']);
    
    Route::post('/recipes', [RecipeController::class,'store']);
    Route::get('/recipes/{id}', [RecipeController::class,'show']);
    Route::delete('/recipes/{id}', [RecipeController::class,'delete']);
    Route::patch('/recipes/{id}', [RecipeController::class,'update']);
    
    Route::post('/recipes/upload', [RecipeController::class,'upload']);
    Route::get('/users/{id}',[UserController::class,'show']);


Route::post('/cart', [CartController::class, 'addToCart']);
Route::get('/cart', [CartController::class, 'show']);
Route::get('/bestSeller', [RecipeController::class, 'bestSeller']);
Route::post('/cart/updateQuantity', [CartController::class, 'updateQuantity']);
Route::delete('/cart/{recipeId}', [CartController::class, 'remove']);

Route::get('/charge', function () {
    return view('charge');
});

});









 


