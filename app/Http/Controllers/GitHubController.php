<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Traits\HttpResponses;


class GitHubController extends Controller
{
    use HttpResponses;

    public function redirect(){
        
      return Socialite::with('github')->stateless()->redirect()->getTargetUrl();
    }

public function callback(){
    $user = Socialite::with('github')->stateless()->user();

    $gitUser = User::updateOrCreate([
        'github_id' => $user->id

    ],[
        'name' => $user->name,
        'email'=> $user->email,
        'remember_token'=> $user->token,
        'auth_type'=> 'github',
        'password'=> Hash::make(str(10))
    ]);

    Auth::login($gitUser);
    $token = $gitUser->createToken('authToken')->plainTextToken;


    return redirect('http://localhost:5173')->cookie('bearertoken', $token, 0, '/', null, false, false)
    ->cookie('user_id', $gitUser->id, 0, '/', null, false, false);
    



    // return $this->success([
    //     'user'=> $gitUser,
    //     'token'=> $user->token
    // ])->redirect('http://localhost:5173');


}
}
