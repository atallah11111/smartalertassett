<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-lg p-10 animate-fadeIn">

            {{-- Logo --}}
            <div class="flex justify-center mb-6">
                <img src="{{ asset('assets/logo-kominfo.png') }}" alt="Logo Kominfo" class="h-16 w-auto">
            </div>

            {{-- Header --}}
            <div class="text-center mb-6">
                <h2 class="text-2xl font-extrabold text-gray-800">Verifikasi OTP</h2>
                <p class="text-gray-500 mt-2 text-sm">Masukkan kode OTP yang dikirim ke WhatsApp kamu.</p>
            </div>

            {{-- Status --}}
            @if(session('status'))
            <div class="mb-4 text-green-700 bg-green-100 border border-green-300 rounded-lg p-3 text-sm">
                {{ session('status') }}
            </div>
            @endif

            {{-- Global Error --}}
            @if($errors->has('otp_code'))
            <div class="mb-4 text-red-700 bg-red-100 border border-red-300 rounded-lg p-3 text-sm">
                {{ $errors->first('otp_code') }}
            </div>
            @endif

            {{-- OTP Form --}}
            <form method="POST" action="{{ route('otp.verify') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="otp_code" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode OTP
                    </label>
                    <input id="otp_code"
                           name="otp_code"
                           type="text"
                           inputmode="numeric"
                           maxlength="6"
                           pattern="[0-9]*"
                           value="{{ old('otp_code') }}"
                           required
                           class="block w-full p-3 rounded-xl border @error('otp_code') border-red-400 @else border-gray-300 @enderror
                                    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                           placeholder="123456">
                    <p class="text-xs text-gray-500 mt-1">*Hanya angka, maksimal 6 digit</p>
                </div>

                <button type="submit"
                    class="w-full py-3 text-white font-semibold rounded-xl bg-gradient-to-r from-indigo-600 to-purple-700
                           hover:opacity-90 hover:scale-[1.02] transition duration-300 shadow-lg">
                    Verifikasi OTP
                </button>
            </form>

            {{-- Resend OTP --}}
            <div class="mt-6 text-center">
                <form method="POST" action="{{ route('otp.resend') }}">
                    @csrf
                    <button type="submit" class="text-sm text-indigo-600 hover:underline">
                        Kirim ulang kode OTP
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- Animasi Fade --}}
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn .4s ease-out;
        }
    </style>
</x-guest-layout>
