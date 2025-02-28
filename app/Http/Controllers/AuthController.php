<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function tampilRegisterasi(){
        return view('registerasi');
    }
    function submitRegisterasi(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Pastikan password diulang
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')), // **Pakai Hash::make()**
        ]);
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');

    }

    function tampilLogin(){
        return view('login'); 
    }
    function submitLogin(Request $request){
        $data = $request->only('email', 'password');

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if (Auth::attempt($data)){
            $request->session()->regenerate();
            return redirect()->route('tasks.index');
        }

            return redirect()->back()->with('gagal', 'email  atau password anda salah');
        
    }

    function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}

