<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User; // Pastikan import Model User
use Illuminate\Support\Facades\Auth;

class SocialiteController extends Controller
{
    // Menerima parameter $provider dari route '{provider}'
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Menerima parameter $provider dari route '{provider}'
    public function callback($provider)
    {
        try {
            // Ambil user dari Google/Provider lain
            $socialUser = Socialite::driver($provider)->user();

            // Cek apakah user sudah ada berdasarkan email
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                // Jika belum ada, buat user baru (Registrasi otomatis)
                // Sesuaikan field ini dengan database kamu
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'role' => 'user', // Default role
                    'password' => bcrypt('password_default_dari_socialite'), // Atau random string
                    'email_verified_at' => now(),
                ]);
            }

            // Login user tersebut
            Auth::login($user);

            // Redirect ke dashboard
            return redirect('/dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan ' . $provider . ': ' . $e->getMessage());
        }
    }
}
