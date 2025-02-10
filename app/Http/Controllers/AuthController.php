<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function tampilRegisterasi(){
        return view('registerasi');
    }
    function submitRegisterasi(Request $request){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->route('login');

    }

    function tampilLogin(){
        return view('login');
    }
    function submitLogin(Request $request){
        $data = $request->only('email', 'password');

        if (Auth::attempt($data)){
            $request->session()->regenerate();
            return redirect()->route('tasks.index');
        }else{
            return redirect()->back()->with('gagal', 'email  atau password anda salah');
        }
    }

    function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}

