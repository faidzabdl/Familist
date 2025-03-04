<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;  

        $user = User::where('id', $user_id)->first();

        if($user->skor <=10){
            $user->update(['tittle' => 'tingkatkan lagi']);
        }elseif($user->skor <=50){
            $user->update(['tittle' => 'mulai rajin nih']);
        }elseif($user->skor <=100){
            $user->update(['tittle' => 'makin hebat']);
        }elseif($user->skor >100){
            $user->update(['tittle' => 'legenda hidup']);
        }
        // $user->save();
        // dd($user);
        return view('profile', compact('user')); 
    }

    public function setting()
    {
        $user = Auth::user();
        return view('account', compact('user')); 
    }

    public function update(Request $request)
    {
        $user = Auth::user();
    
        if ($user instanceof User) {
       
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile_pic' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'  
        ]);

        if ($request->hasFile('profile_pic')) {
            Storage::disk('public')->delete($user->profile_pic);
            $path = $request->file('profile_pic')->store('profile-pic', 'public');
            $user->profile_pic = $path; 
        }
    
       
        if ($request->filled('name')) {
            $user->name = $request->name;
        }
    
        
        if ($request->filled('email')) {
            $user->email = $request->email;
        }
    
       
        if ($request->filled('password')) {

            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Password lama salah!']);
            }
            $user->password = bcrypt($request->password);
        }
    
        
        $user->save();
    
        return redirect()->route('account.setting')->with('success', 'Profil berhasil di update!');
    }else{
        return redirect()->route('account.setting')->with('error', 'User tidak di temukan');
    }
    }

    public function leaderboard() {
        
        
        $user = User::orderBy('skor', 'DESC')->get();
        return view('leaderboard', compact('user'));
    }
    
}
