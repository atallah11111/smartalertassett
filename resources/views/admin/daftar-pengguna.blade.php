<x-app-layout>
    <div class="bg-white rounded-xl p-6 text-center">
        <!-- Icon dan Header -->
        <div class="flex justify-center mb-4">
            <div class="bg-gradient-to-br from-purple-600 to-indigo-600 p-4 rounded-full text-white shadow-lg w-16 h-16 flex items-center justify-center">
                <i class="fa fa-users text-2xl"></i>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
        <p class="text-sm text-gray-600">Kelola semua pengguna sistem dan atur hak akses pengguna.</p>

        <!-- Tombol Aksi -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-6">
            <button onclick="document.getElementById('createUserModal').classList.remove('hidden')"
                class="w-full inline-flex justify-center items-center px-4 py-2 text-white font-semibold bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg shadow hover:scale-105 transition">
                <i class="fa fa-user-plus mr-2"></i> Create User
            </button>
            <button onclick="document.getElementById('filterUserModal').classList.remove('hidden')"
                class="w-full inline-flex justify-center items-center px-4 py-2 text-white font-semibold bg-gradient-to-r from-green-400 to-teal-500 rounded-lg shadow hover:scale-105 transition">
                <i class="fa fa-users mr-2"></i> All Users
            </button>
        </div>

        <!-- Tabel User -->
        <!-- <div class="overflow-x-auto rounded-xl shadow border border-gray-200 bg-white text-gray-800 mt-6">
            <table class="min-w-full text-sm text-left">
                <thead>
                    <tr class="text-white text-sm">
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 rounded-tl-xl">No</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Nama</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Email</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Role</th>
                        <th class="px-4 py-3 text-center bg-gradient-to-r from-blue-600 to-purple-600 rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $index => $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3 capitalize">{{ $user->role }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full hover:bg-blue-200 transition">
                                        <i class="fa fa-pen"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 bg-red-100 text-red-800 text-xs font-medium px-3 py-1 rounded-full hover:bg-red-200 transition">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-4 py-4 text-gray-500">Tidak ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div> -->

  
    <div class="overflow-x-auto rounded-xl shadow border border-gray-200 bg-white text-gray-800 p-5 mt-4">
    <table id="myTable" class="min-w-full text-sm">
            <thead>
                <tr class="text-white text-sm">
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 rounded-tl-xl text-center">No</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Nama</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Email</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600">Role</th>
                        <th class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 rounded-tr-xl">Aksi</th>

                    
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($users as $i => $user)
                    <tr class="hover:bg-purple-50 transition">
                        <td class="px-4 py-3">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3 capitalize">{{ $user->role }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full hover:bg-blue-200 transition">
                                    <i class="fa fa-pen"></i>Edit
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 bg-red-100 text-red-800 text-xs font-medium px-3 py-1 rounded-full hover:bg-red-200 transition">
                                        <i class="fa fa-trash"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>

        </div>
    </table>
</div>
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: true,
                pageLength: 5,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
                }
            });
        });
    </script>
    @endpush

    

    <!-- Modal Create User -->
    <div id="createUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <!-- Tombol Close -->
            <button onclick="document.getElementById('createUserModal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">
                ×
            </button>

            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Create New User</h2>

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <x-label for="name" value="{{ __('Name') }}" />
                    <x-input id="name" class="text-black block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block text-black mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autocomplete="username" />
                </div>
                <div class="mb-4">
                    <x-label for="nomor" value="{{ __('Nomor Telepon') }}" />
                    <x-input id="nomor" class="block text-black mt-1 w-full" type="text" name="nomor" :value="old('nomor')" required autocomplete="tel" placeholder="Contoh: 6281234567890" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="text-black block mt-1 w-full" type="password" name="password"
                        required autocomplete="new-password" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-input id="password_confirmation" class="block text-black mt-1 w-full" type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <x-label for="role" value="{{ __('Register as') }}" />
                    <select id="role" name="role" required
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-black">
                        <option value="user" @selected(old('role') == 'user')>User</option>
                        <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                    </select>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white py-2 rounded-lg hover:opacity-90">
                    Create User
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Filter User -->
    <div id="filterUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <!-- Tombol Close -->
            <button onclick="document.getElementById('filterUserModal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">
                ×
            </button>

            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Filter Users by Role</h2>

            <form method="GET" action="{{ route('admin.users.index') }}">
                <!-- Role -->
                <div class="mb-4">
                    <x-label for="role" value="{{ __('Select Role') }}" />
                    <select id="role" name="role" required
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-black">
                        <option value="all">Semua</option>
                        <option value="admin" @selected(request('role') == 'admin')>Admin</option>
                        <option value="user" @selected(request('role') == 'user')>User</option>
                    </select>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-green-400 to-teal-500 text-white py-2 rounded-lg hover:opacity-90">
                    Filter
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
