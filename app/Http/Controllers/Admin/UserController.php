<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Documents;
use App\Models\DocumentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Tampilkan dashboard admin + daftar user
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter role user/admin
        if ($request->filled('role') && in_array($request->role, ['admin', 'user'])) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        // Statistik global
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();

        $totalDocuments = Documents::count();
        $alertThreshold = 7; // contoh: H-7 hari
        $expiringDocuments = Documents::where('tanggal_expired', '<=', Carbon::now()->addDays($alertThreshold))->count();

        // Dokumen terbaru
        $recentDocuments = Documents::orderBy('created_at', 'desc')->take(5)->get();
        $recentDocuments->transform(function ($doc) {
            $doc->tanggal_expired = Carbon::parse($doc->tanggal_expired);
            return $doc;
        });

        // Log dokumen terbaru
        $recentDocumentLogs = DocumentLog::with('document')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.daftar-pengguna', compact(
            'users',
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'totalDocuments',
            'expiringDocuments',
            'recentDocuments',
            'alertThreshold',
            'recentDocumentLogs'
        ));
    }

    /**
     * Form tambah user
     */
    public function create()
    {
        return view('admin.user-create');
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'nomor' => 'required|string|max:20',    
            'password' => 'required|confirmed|min:6',
            'role'     => 'required|in:admin,user',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'nomor' => $request->nomor,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Form edit user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user-edit', compact('user'));
    }

    /**
     * Update data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|confirmed|min:6',
            'role'     => 'required|in:admin,user',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Optional: jika user punya dokumen, bisa ditangani dulu
        // Documents::where('user_id', $user->id)->delete();

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
