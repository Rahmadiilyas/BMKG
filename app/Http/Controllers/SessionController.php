<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class SessionController extends Controller
{
  public function index()
{
    if (Auth::check()) {
        return redirect('/Dashboard-Teknisi');
    }
    return view("login");
}
    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.required' => 'Email  wajib diisi',
                'password.required' => 'Password wajib diisi'
            ],
        );
        $infologin = [
            'email' => $request->email,
            'password' => $request->password
        ];
      if (Auth::attempt($infologin, true)) { 
    $request->session()->regenerate();
    return redirect('/Dashboard-Teknisi');
} else {
            return back()->withErrors([
                'email' => 'Email atau password salah'
            ]);
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();                     // keluar dari auth
        $request->session()->invalidate(); // hapus session
        $request->session()->regenerateToken(); // refresh CSRF token

        return redirect('/');
    }

public function sendOTP(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User belum login'
        ], 401);
    }

    $otp = rand(100000, 999999);

    session([
        'auth_otp' => $otp,
        'otp_expires' => now()->addMinutes(10),
    ]);

    try {
        Mail::raw("Kode verifikasi Anda adalah: $otp", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode Verifikasi Akun BMKG');
        });

        return response()->json([
            'success' => true,
            'message' => 'OTP berhasil dikirim'
        ]);

    } catch (\Exception $e) {
        Log::error('Email OTP Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Gagal kirim OTP. Cek log server.'
        ], 500);
    }
}


public function updateAccount(Request $request) 
{
    if (!session('auth_otp')) {
        return back()->withErrors(['otp' => 'OTP belum dikirim']);
    }

    if (now()->gt(session('otp_expires'))) {
        return back()->withErrors(['otp' => 'OTP sudah kadaluarsa']);
    }

    if ($request->otp != session('auth_otp')) {
        return back()->withErrors(['otp' => 'Kode OTP salah!']);
    }

    $user = Auth::user();

    if ($request->update_type == 'email') {
        $request->validate(['new_email' => 'required|email|unique:users,email']);
        $user->email = $request->new_email;
    } 
    else if ($request->update_type == 'password') {
        $request->validate(['new_password' => 'required|min:8']);
        $user->password = Hash::make($request->new_password);
    }

    $user->save();

    session()->forget(['auth_otp', 'otp_expires']);

    return redirect()->back()->with('success', 'Data berhasil diperbarui');
}

}
