<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Otp;
use Exception;

class OtpLoginController extends Controller
{
    /**
     * Helper: format nomor ke 62xxxx
     */
    protected function formatToInternational(string $number): string
    {
        $number = preg_replace('/\D/', '', $number);

        if (substr($number, 0, 1) === '0') {
            return '62' . substr($number, 1);
        }

        if (substr($number, 0, 3) === '+62') {
            return substr($number, 1);
        }

        if (substr($number, 0, 2) === '62') {
            return $number;
        }

        return $number;
    }

    /**
     * Show OTP input form
     */
    public function showForm()
    {
        if (!Session::has('OTP_USER')) {
            return redirect()->route('login')
                ->withErrors(['otp' => 'Silakan login terlebih dahulu!']);
        }

        return view('auth.otp');
    }

    /**
     * Send OTP request
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::where('email', $request->email)->first();
            $otpCode = random_int(100000, 999999);
            $expiresAt = now()->addMinutes(5);

            // Tandai semua OTP sebelumnya sebagai used
            Otp::where('user_id', $user->id)
                ->where('used', false)
                ->update(['used' => true]);

            // Insert OTP baru
            Otp::create([
                'user_id' => $user->id,
                'code' => $otpCode,
                'expires_at' => $expiresAt,
                'used' => false,
            ]);

            Session::put('OTP_USER', ['email' => $user->email]);

            // Kirim WA
            $wa_number = $user->nomor ?? null;
            if ($wa_number) {
                $formattedNumber = $this->formatToInternational($wa_number);

                $payload = [
                    'messages' => [
                        [
                            'number' => $formattedNumber,
                            'message' => "Kode OTP login Anda: *{$otpCode}*\nBerlaku sampai " . $expiresAt->format('H:i') . " WIB."
                        ]
                    ]
                ];

                Http::post('http://localhost:3000/send-message', $payload);
                Session::flash('success', 'Kode OTP berhasil dikirim ke WhatsApp.');
            } else {
                Session::flash('warning', 'Nomor WhatsApp tidak ditemukan. Kode OTP: ' . $otpCode);
            }

            return redirect()->route('otp.form');

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat kirim OTP.');
        }
    }

    /**
     * Resend OTP handler
     */
    public function resend(Request $request)
    {
        $userData = Session::get('OTP_USER');

        if (!$userData || !isset($userData['email'])) {
            return redirect()->route('login')->withErrors(['otp' => 'Sesi OTP kedaluwarsa']);
        }

        $request->merge(['email' => $userData['email']]);
        return $this->sendOtp($request);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp_code' => ['required', 'digits:6'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userData = Session::get('OTP_USER');

        if (!$userData) {
            return redirect()->route('login')->withErrors(['otp' => 'Sesi OTP tidak valid.']);
        }

        $user = User::where('email', $userData['email'])->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['otp' => 'Akun tidak ditemukan.']);
        }

        $otpRecord = Otp::where('user_id', $user->id)
            ->where('code', $request->otp_code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp_code' => 'Kode OTP salah atau kadaluarsa.']);
        }

        // Update status OTP
        $otpRecord->update(['used' => true]);

        // Tandai user verified (ajak timmu sesuaikan field ini!)
        $user->update(['is_verified' => true]);

        Auth::login($user);
        Session::forget('OTP_USER');

        return redirect()->intended('/dashboard')->with('success', 'Berhasil login!');
    }
}
