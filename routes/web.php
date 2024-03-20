<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

 
Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});
 
Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();

    $gitUser = User::updateOrCreate([
        'github_id' => $user->id
    ],[
        'name'=> $user->name,
        'nickname'=> $user->nickname,
        'email' => $user->email,
        "github_token"=> $user->token,
        "auth_type"=> 'github',
        "password"=> '111'

    ]);


    Auth::login($gitUser);
    // $user->token
});

Route::get('/auth/logout', function () {
     Auth::logout();
    return redirect('http://localhost:5173/');
});




