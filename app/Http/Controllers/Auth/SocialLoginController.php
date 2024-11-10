<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
class SocialLoginController extends Controller
{


    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callback(){
        $user = Socialite::driver('google')->user();

        //dd($user);

        $user_db = User::firstOrCreate([
            'email'=>$user->email,
            'google_id'=>$user->id,

        ],[
            'name'=>$user->name,
            'user_name'=>Str::replace(' ','',$user->name),
            'email'=>$user->email,
            'google_token'=>$user->token,
            'password'=>Hash::make(Str::random(8)),
        ]);

        Auth::login($user_db);
        return redirect()->route('dashboard');
    }
}
