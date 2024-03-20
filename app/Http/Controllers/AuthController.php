<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request){
        $request->validated($request->all());
        if(!Auth::attempt($request->only(['email','password']))){
            return $this->error('','Credentials do not match',401);
        }

        $user = User::where('email',$request->email)->first();
        //verify is this admin
        return $this->success([
            'user'=> $user,
            'token'=> $user->createToken('API token of ' . $user->name)->plainTextToken
        ]);
    }

    public function register(StoreUserRequest $request){
        $request->validated($request->all());
        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);
        return $this->success([
            'user'=> $user,
            'token'=> $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function logout(){
        return 'This is';
    }
}

// public function callback(){
//     $user = Socialite::with('github')->stateless()->user();

//     $gitUser = User::updateOrCreate([
//         'github_id' => $user->id

//     ],[
//         'name' => $user->name,
//         'email'=> $user->email,
//         'remember_token'=> $user->token,
//         'auth_type'=> 'github',
//         'password'=> Hash::make(str(10))
//     ]);

//     Auth::login($gitUser);
//     $token = $gitUser->createToken('authToken')->plainTextToken;


//     return redirect('http://localhost:5173')->cookie('bearertoken', $token, 0, '/', null, false, false)
//     ->cookie('user_id', $gitUser->id, 0, '/', null, false, false);
    



//     // return $this->success([
//     //     'user'=> $gitUser,
//     //     'token'=> $user->token
//     // ])->redirect('http://localhost:5173');


// }