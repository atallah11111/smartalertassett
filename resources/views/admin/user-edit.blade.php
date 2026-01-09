<x-app-layout>
    <h2 class="text-xl font-bold mb-4">Edit User</h2>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border p-2 rounded" required>
        </div>

       <div>
    <label class="block font-medium">Password <span class="text-red-500">*</span></label>
    <input type="password" name="password" class="w-full border p-2 rounded" required>
    @error('password')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<div>
    <label class="block font-medium">Konfirmasi Password <span class="text-red-500">*</span></label>
    <input type="password" name="password_confirmation" class="w-full border p-2 rounded" required>
    @error('password_confirmation')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Update</button>
    </form>
</x-app-layout>
