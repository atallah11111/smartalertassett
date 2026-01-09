<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    // Tampilkan halaman login
    public function create()
    {
        return view('auth.login');
    }

    // Proses login
    public function store(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ], [
        'email.required'    => 'Email wajib diisi.',
        'email.email'       => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
    ]);

    // Coba login
    if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
        $request->session()->regenerate();

        // Jalankan proses OTP via WA
        return $this->authenticated($request, Auth::user());
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
}

    // Logout
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Jalankan OTP setelah login berhasil
    protected function authenticated(Request $request, User $user)
    {
        // Reset verifikasi
        $user->update(['is_verified' => false]);

        // Generate OTP 6 digit
        $code = rand(100000, 999999);

        // Simpan ke tabel otps
        $user->otps()->create([
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Logout sementara
        auth()->logout();

        // Simpan user sementara di session
        session(['otp_user_id' => $user->id]);

        // Debug OTP
        Log::info('OTP DEBUG: akan dikirim ke WA', [
            'user_id' => $user->id,
            'number' => $user->nomor,  // <-- ganti di sini
            'code' => $code,
        ]);

        // Kirim OTP via Node.js WA
        if ($user->nomor) {
            try {
                $number = $user->nomor;
                if (str_starts_with($number, '0')) {
                    $number = '62' . substr($number, 1);
                }

                $message = "Hai {$user->name}, kode OTP login kamu adalah: *{$code}*.\nBerlaku 5 menit.";

                $response = Http::post('http://127.0.0.1:3000/send-message', [
                    'number' => $number,
                    'message' => $message,
                ]);

                Log::info('OTP DEBUG Response Node.js', [
                    'body' => $response->body(),
                    'status' => $response->status(),
                ]);
            } catch (\Exception $e) {
                Log::error('OTP DEBUG Gagal kirim request ke Node.js', [
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            Log::error('OTP DEBUG: nomor WA kosong untuk user ' . $user->id);
        }

        return redirect()->route('otp.form')->with('status', 'Kode OTP telah dikirim ke WhatsApp kamu.');
    }

    // Kirim ulang OTP
    public function sendOtp(Request $request)
    {
        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors('User tidak ditemukan di session.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->withErrors('User tidak ditemukan.');
        }

        $code = rand(100000, 999999);
        $user->otps()->create([
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        Log::info('OTP DEBUG (resend): akan dikirim ke WA', [
            'user_id' => $user->id,
            'number' => $user->nomor,  // <-- ganti di sini
            'code' => $code,
        ]);

        if ($user->nomor) {
            try {
                $number = $user->nomor;
                if (str_starts_with($number, '0')) {
                    $number = '62' . substr($number, 1);
                }

                $message = "Hai {$user->name}, kode OTP login kamu adalah: *{$code}*.\nBerlaku 5 menit.";

                $response = Http::post('http://127.0.0.1:3000/send-message', [
                    'number' => $number,
                    'message' => $message,
                ]);

                Log::info('OTP DEBUG Response Node.js (resend)', [
                    'body' => $response->body(),
                    'status' => $response->status(),
                ]);
            } catch (\Exception $e) {
                Log::error('OTP DEBUG Gagal kirim request ke Node.js (resend)', [
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            Log::error('OTP DEBUG: nomor WA kosong untuk user ' . $user->id);
        }

        return redirect()->route('otp.form')->with('status', 'Kode OTP baru telah dikirim ke WhatsApp kamu.');
    }
}
