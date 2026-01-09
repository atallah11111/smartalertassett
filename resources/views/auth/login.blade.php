<x-guest-layout>
    <div class="flex min-h-screen bg-gray-50">

        {{-- Left Section --}}
        <div
            class="hidden lg:flex w-1/2 text-white items-center justify-center p-10 relative overflow-hidden"
            style="
                background: linear-gradient(-45deg, #4c1d95, #7c3aed, #db2777, #2563eb, #f59e0b);
                background-size: 600% 600%;
                animation: gradientBG 6s ease infinite;
                clip-path: polygon(0 0, 100% 0, 90% 100%, 0% 100%);
            ">

            <div class="absolute -top-20 -left-20 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
            <div class="absolute -bottom-32 -right-20 w-72 h-72 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
            <div class="absolute top-1/3 right-1/4 w-72 h-72 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>

            <div class="max-w-md relative z-10 text-left">
                <h1 class="text-4xl font-extrabold mb-4 leading-tight">
                    <span class="text-white">SmartAssetAlert</span>
                </h1>
                <p class="text-sm leading-relaxed opacity-90">
                    Platform resmi <span class="font-semibold">Pemerintah</span> untuk pengingat aset melalui WhatsApp.
                    Dengan sistem ini, pengelolaan aset menjadi lebih <span class="font-semibold">efektif, efisien, dan transparan</span>.
                </p>

                <p class="mt-10 text-sm opacity-70">© 2025 Pemerintah Republik Indonesia. All rights reserved.</p>
            </div>
        </div>

        {{-- Right Section (Login Form) --}}
        <div class="flex w-full lg:w-1/2 items-center justify-center p-10">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 transform transition duration-500 hover:scale-[1.02]">

                {{-- Logo --}}
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('assets/logo-kominfo.png') }}" alt="Logo Kominfo" class="h-16">
                </div>

                <h2 class="text-xl font-bold text-gray-800 text-center mb-6">Login via WhatsApp OTP</h2>

                {{-- Status --}}
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 animate-fadeIn">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- All Error --}}
                @if($errors->any())
                    <div class="mb-4 text-red-500 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Login Form (Email & Password -> WhatsApp OTP) --}}
                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="block w-full p-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input id="password" type="password" name="password" required
                            class="block w-full p-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition">
                    </div>

                    {{-- Submit Button (OTP) --}}
                    <div class="flex flex-col gap-3 mt-6">
                        <button type="submit"
                            class="w-full text-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-700 text-white py-3 font-semibold hover:opacity-90 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                            Login
                        </button>
                    </div>
                </form>

                <!-- Pemisah antara Login Tradisional dan OAuth -->
                <div class="flex items-center my-5">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="flex-shrink mx-4 text-gray-500 text-sm">ATAU</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>

                <!-- Tombol Login Google (OAuth) -->
                <div class="mt-4">
                    <a href="{{ route('socialite.redirect', 'google') }}"
                       class="w-full flex items-center justify-center text-center rounded-xl bg-white border border-gray-300 text-gray-700 py-3 font-semibold hover:bg-gray-100 transition duration-300 shadow-sm">

                        <!-- Ikon Google menggunakan SVG untuk Tailwind yang bersih -->
                        <svg class="w-5 h-5 mr-3" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M43.611 20.083H42V20H24V28H36.444C35.278 31.861 32 34.611 27.556 34.611C21.667 34.611 16.889 29.833 16.889 24C16.889 18.167 21.667 13.389 27.556 13.389C30.889 13.389 33.389 14.778 35.111 16.389L37.778 13.722C34.333 10.5 30.056 8.889 27.556 8.889C18.667 8.889 11.222 15.611 11.222 24C11.222 32.389 18.667 39.111 27.556 39.111C36.444 39.111 43.611 32.389 43.611 24C43.611 23.333 43.556 22.722 43.444 22.111C43.333 21.5 43.278 20.889 43.278 20.278L43.611 20.083Z" fill="#FFC107"/>
                            <path d="M16.889 24.001C16.889 25.112 17.056 26.167 17.333 27.112L11.722 31.445C10.778 29.834 10.222 27.945 10.222 26.001C10.222 22.056 11.667 18.445 13.833 15.612L16.889 24.001Z" fill="#FF3D00"/>
                            <path d="M27.556 8.889C30.056 8.889 32.889 9.833 35.111 11.778L37.778 9.111C34.333 5.944 30.056 4.333 27.556 4.333C18.667 4.333 11.222 11.056 11.222 19.389C11.222 20.833 11.5 22.222 12.056 23.556L14.722 26.222C14.056 25.111 13.722 23.889 13.722 22.611C13.722 18.111 17.111 13.333 21.889 13.333C24.444 13.333 26.667 14.333 27.556 15.667L27.556 8.889Z" fill="#4CAF50"/>
                            <path d="M24 28H36.444C36.222 28.5 36.111 29.056 36 29.611L36.444 34.611C32 34.611 27.556 30.611 27.556 24.889C27.556 24.111 27.667 23.333 27.889 22.611H24V28Z" fill="#1976D2"/>
                        </svg>

                        Login dengan Google
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- CSS Animations --}}
    <style>
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(40px, -60px) scale(1.2); }
            66% { transform: translate(-30px, 30px) scale(0.8); }
        }

        .animate-blob { animation: blob 10s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out both;
        }
    </style>
</x-guest-layout>
