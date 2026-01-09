<aside
    class="fixed top-4 start-4 z-50 w-64 h-[calc(100vh-2rem)] 
           bg-white shadow-xl rounded-xl transition-all duration-300">

    <!-- Logo -->
    <div class="px-6 py-4 flex items-center">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('assets/logo-kominfo.png') }}" class="h-9 w-auto" alt="Logo">
            <span class="font-bold text-slate-900 text-[18px]">SmartAssetAlert</span>
        </a>
    </div>

    <hr class="my-2 border-slate-200">

    <!-- Sidebar Menu -->
    <div class="flex-1 overflow-y-auto px-4">
        <ul class="space-y-1 pb-4">

            <!-- ================= DASHBOARD ================= -->
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-semibold
                    {{ request()->routeIs('dashboard') 
                        ? 'bg-gradient-to-tr from-purple-700 to-purple-500 text-white shadow-md' 
                        : 'text-slate-700 hover:bg-gray-100' }}">
                    <i class="fas fa-home w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- ================= ADMIN MENU ================= --}}
            @if(auth()->check() && auth()->user()->role === 'admin')
                <li class="pt-4 ps-2 text-[11px] font-bold uppercase text-gray-400">
                    Admin Menu
                </li>

                <!-- Manajemen User -->
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-semibold
                        {{ request()->routeIs('admin.users.*') 
                            ? 'bg-gradient-to-tr from-purple-700 to-purple-500 text-white shadow-md' 
                            : 'text-slate-700 hover:bg-gray-100' }}">
                        <i class="fas fa-users w-5 text-center"></i>
                        <span>Manajemen User</span>
                    </a>
                </li>

                <!-- Manajemen Dokumen -->
                <li>
                    <a href="{{ route('admin.documents.index') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-semibold
                        {{ request()->routeIs('admin.documents.*') 
                            ? 'bg-gradient-to-tr from-purple-700 to-purple-500 text-white shadow-md' 
                            : 'text-slate-700 hover:bg-gray-100' }}">
                        <i class="fas fa-boxes w-5 text-center"></i>
                        <span>Manajemen Dokumen</span>
                    </a>
                </li>

                <!-- Log Aktivitas (ADMIN lihat semua log) -->
                <li>
                    <a href="{{ route('document-logs.index') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-semibold
                        {{ request()->routeIs('document-logs.index') 
                            ? 'bg-gradient-to-tr from-purple-700 to-purple-500 text-white shadow-md' 
                            : 'text-slate-700 hover:bg-gray-100' }}">
                        <i class="fas fa-clock w-5 text-center"></i>
                        <span>Log Aktivitas</span>
                    </a>
                </li>
            @endif

            {{-- ================= USER MENU ================= --}}
            @if(auth()->check() && auth()->user()->role === 'user')
                <li class="pt-4 ps-2 text-[11px] font-bold uppercase text-gray-400">
                    User Menu
                </li>

                <!-- Tugas Saya -->
                <li>
                    <a href="{{ route('user.documents.tasks') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-semibold
                        {{ request()->routeIs('user.documents.tasks') 
                            ? 'bg-gradient-to-tr from-purple-700 to-purple-500 text-white shadow-md' 
                            : 'text-slate-700 hover:bg-gray-100' }}">
                        <i class="fas fa-tasks w-5 text-center"></i>
                        <span>Tugas Saya</span>
                    </a>
                </li>

                <!-- Dokumen Saya -->
                <li>
                    <a href="{{ route('user.documents') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-semibold
                        {{ request()->routeIs('user.documents') 
                            ? 'bg-gradient-to-tr from-purple-700 to-purple-500 text-white shadow-md' 
                            : 'text-slate-700 hover:bg-gray-100' }}">
                        <i class="fas fa-file-alt w-5 text-center"></i>
                        <span>Dokumen Saya</span>
                    </a>
                </li>

                <!-- Log Aktivitas (USER lihat log sendiri) -->
                <li>
                    <a href="{{ route('user.logs') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-semibold
                        {{ request()->routeIs('user.logs') 
                            ? 'bg-gradient-to-tr from-purple-700 to-purple-500 text-white shadow-md' 
                            : 'text-slate-700 hover:bg-gray-100' }}">
                        <i class="fas fa-history w-5 text-center"></i>
                        <span>Log Aktivitas</span>
                    </a>
                </li>
            @endif

            {{-- ================= PROFILE ================= --}}
            @if(auth()->check())
                <li class="pt-4 border-t border-gray-200 mt-4">
                    <a href="{{ route('profile.show') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-semibold
                        {{ request()->routeIs('profile.show') 
                            ? 'bg-gradient-to-tr from-purple-700 to-purple-500 text-white shadow-md' 
                            : 'text-slate-700 hover:bg-gray-100' }}">
                        <i class="fas fa-user w-5 text-center"></i>
                        <span>Profil</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- ================= LOGOUT ================= -->
    @if(auth()->check())
        <div class="px-4 py-4 border-t border-gray-200 mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center w-full gap-3 px-4 py-2 rounded-lg 
                           text-sm font-semibold text-red-600 hover:bg-red-100">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    @endif
</aside>
