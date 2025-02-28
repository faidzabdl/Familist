<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showResetForm($token, $email)
    {
        return view('reset-password', ['token' => $token, 'email' => $email]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        // Cek apakah token valid
        $resetData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetData) {
            return back()->withErrors(['email' => 'Token tidak valid atau sudah kedaluwarsa.']);
        }

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token setelah digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset!');
    }
}
