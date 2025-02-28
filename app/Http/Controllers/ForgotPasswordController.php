<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // Generate token unik
        $token = Str::random(60);

        // Simpan token ke database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Kirim email reset password
        Mail::send('reset-password-email', ['token' => $token, 'email' => $request->email], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password Anda');
        });

        return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
    }
}

