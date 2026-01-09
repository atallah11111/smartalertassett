<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\DocumentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class DocumentController extends Controller
{

public function index()
{
    $userId = Auth::id();

    // semua dokumen user
    $documents = Documents::where('user_id', $userId)->get();

    // total dokumen user
    $myDocuments = $documents->count();

    // tugas aktif = dokumen yang belum expired
    $activeTasks = $documents->filter(function ($doc) {
        return !$doc->isExpired();
    })->count();

    // daftar dokumen terbaru
    $latestDocuments = $documents->sortByDesc('created_at')->take(5);

    // cari dokumen yang masuk periode notifikasi
    $userNotifications = $documents->filter(function ($doc) {
        if (!$doc->tanggal_expired || !$doc->diperingatkan_h) {
            return false;
        }

        // hitung tanggal mulai peringatan
        $reminderDate = $doc->tanggal_expired->copy()->subDays($doc->diperingatkan_h);

        // notifikasi kalau sekarang >= tanggal mulai peringatan dan belum expired
        return now()->between($reminderDate, $doc->tanggal_expired);
    });

    // jumlah notifikasi
    $myNotifications = $userNotifications->count();

    // hitung status untuk chart
    $expiredCount = $documents->filter->isExpired()->count();
    $expiringSoonCount = $documents->filter->isExpiringSoon()->count();
    $safeCount = $myDocuments - $expiredCount - $expiringSoonCount;

    return view('dashboard-user', compact(
        'myDocuments',
        'activeTasks',
        'myNotifications',
        'latestDocuments',
        'userNotifications',
        'expiredCount',
        'expiringSoonCount',
        'safeCount'
    ));
}


public function adminIndex()
{
    // Ambil 10 dokumen terbaru
    $recentDocuments = Documents::latest()->take(10)->get();

    // Ambil 7 hari terakhir
    $days = collect(range(6, 0))->map(function($i){
        return Carbon::today()->subDays($i)->format('Y-m-d');
    });

    // Hitung jumlah dokumen per hari
    $documentCounts = $days->map(function($day){
        return Documents::whereDate('created_at', $day)->count();
    });

    // Kirim ke view
    return view('admin.daftar-dokumen', compact('recentDocuments', 'days', 'documentCounts'));
}

    public function create()
    {
        return view('documents.create');
    }

   public function store(Request $request)
    {
        $request->validate([
            'nama_dokumen'                  => 'required|string|max:255',
            'jenis_dokumen'                 => 'required|string|max:255',
            'upload_dokumen'                => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'nama_pic'                      => 'required|array',
            'nama_pic.*'                    => 'required|string|max:255',
            'nomor_pic'                     => 'required|array',
            'nomor_pic.*'                   => 'required|string|max:255',
            'tanggal_expired'               => 'required|date',
            'diperingatkan_h'               => 'nullable|integer',
            'ingatkan_setelah_kadaluwarsa'  => 'required|in:ya,tidak',
            'status'                        => 'nullable|in:aktif,tidak aktif'
        ]);

        $filePath = $request->file('upload_dokumen')->store('dokumen', 'public');

        $doc = Documents::create([
            'nama_dokumen'                  => $request->nama_dokumen,
            'jenis_dokumen'                 => $request->jenis_dokumen,
            'upload_dokumen'                => $filePath,
            'nama_pic'                      => implode(', ', $request->nama_pic),
            'nomor_pic'                     => implode(', ', $request->nomor_pic),

            // --- INI BARIS YANG DITAMBAHKAN ---
            'jabatan'                       => '-',
            // ----------------------------------

            'tanggal_expired'               => $request->tanggal_expired,
            'diperingatkan_h'               => $request->diperingatkan_h,
            'ingatkan_setelah_kadaluwarsa'  => $request->ingatkan_setelah_kadaluwarsa,
            'status'                        => $request->status ?? 'aktif',
            'user_id'                       => Auth::id(),
        ]);

        DocumentLog::create([
            'document_id' => $doc->id,
            'user_id' => Auth::id(),
            'event' => 'upload dokumen',
            'status_sebelumnya' => null,
            'status_sekarang' => $doc->status,
            'keterangan' => 'Dokumen diupload oleh user',
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function show(Documents $document)
    {
        return view('documents.show', compact('document'));
    }

    public function edit(Documents $document)
    {
        return view('documents.edit', compact('document'));
    }

   public function update(Request $request, Documents $document)
{
    $request->validate([
        'nama_dokumen'                  => 'required|string|max:255',
        'jenis_dokumen'                 => 'required|string|max:255',
        'upload_dokumen'                => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'nama_pic'                      => 'required|array',
        'nama_pic.*'                    => 'required|string|max:255',
        'nomor_pic'                     => 'required|array',
        'nomor_pic.*'                   => 'required|string|max:255',
        'tanggal_expired'               => 'required|date',
        'diperingatkan_h'               => 'nullable|integer',
        'ingatkan_setelah_kadaluwarsa'  => 'required|in:ya,tidak',
        // hanya validasi status kalau ada
        'status'                        => 'nullable|in:aktif,tidak aktif',
    ]);

    $data = [
        'nama_dokumen'                  => $request->nama_dokumen,
        'jenis_dokumen'                 => $request->jenis_dokumen,
        'nama_pic'                      => implode(', ', $request->nama_pic),
        'nomor_pic'                     => implode(', ', $request->nomor_pic),
        'tanggal_expired'               => $request->tanggal_expired,
        'diperingatkan_h'               => $request->diperingatkan_h,
        'ingatkan_setelah_kadaluwarsa'  => $request->ingatkan_setelah_kadaluwarsa,
    ];

    // hanya update status kalau dikirim (admin)
    if ($request->has('status')) {
        $data['status'] = $request->status;
    }

    if ($request->hasFile('upload_dokumen')) {
        if ($document->upload_dokumen && Storage::disk('public')->exists($document->upload_dokumen)) {
            Storage::disk('public')->delete($document->upload_dokumen);
        }
        $data['upload_dokumen'] = $request->file('upload_dokumen')->store('dokumen', 'public');
    }

    $oldStatus = $document->status;
    $document->update($data);

    DocumentLog::create([
        'document_id' => $document->id,
        'user_id' => Auth::id(),
        'event' => 'update dokumen',
        'status_sebelumnya' => $oldStatus,
        'status_sekarang' => $document->status,
        'keterangan' => 'Dokumen diperbarui oleh ' . (Auth::user()->role ?? 'user'),
    ]);

    return back()->with('success', 'Status berhasil');
}

   public function destroy(Documents $document)
{
    $user = Auth::user();

    // Cek hak akses
    if ($user->role !== 'admin' && $document->user_id !== $user->id) {
        abort(403, 'Anda tidak memiliki izin untuk menghapus dokumen ini.');
    }

    // Buat log
    DocumentLog::create([
        'document_id' => $document->id,
        'user_id' => $user->id,
        'event' => 'hapus dokumen',
        'status_sebelumnya' => $document->status,
        'status_sekarang' => null,
        'keterangan' => $user->role === 'admin'
            ? 'Dokumen dihapus oleh admin'
            : 'Dokumen dihapus oleh user',
    ]);

    // Hapus file jika ada
    if ($document->upload_dokumen && Storage::disk('public')->exists($document->upload_dokumen)) {
        Storage::disk('public')->delete($document->upload_dokumen);
    }

    $document->delete();

    // Redirect sesuai role
    if ($user->role === 'admin') {
        return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
    } else {
        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        $document = Documents::findOrFail($id);
        $oldStatus = $document->status;
        $document->status = $request->status;
        $document->save();

        DocumentLog::create([
            'document_id' => $document->id,
            'user_id' => Auth::id(),
            'event' => 'ubah status',
            'status_sebelumnya' => $oldStatus,
            'status_sekarang' => $request->status,
            'keterangan' => 'Status diperbarui oleh user/admin',
        ]);

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function userDocuments()
    {
        $userDocuments = Documents::where('user_id', auth()->id())->get();
        return view('documents.user-documents', compact('userDocuments'));
    }

    public function tasks()
    {
        $userDocuments = Documents::where('user_id', auth()->id())->get();
        return view('documents.tasks', compact('userDocuments'));
    }
    public function updateFile(Request $request, Documents $document)
{
    $request->validate([
        'upload_dokumen' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
    ]);

    // Hapus file lama jika ada
    if ($document->upload_dokumen && Storage::disk('public')->exists($document->upload_dokumen)) {
        Storage::disk('public')->delete($document->upload_dokumen);
    }

    // Simpan file baru
    $document->upload_dokumen = $request->file('upload_dokumen')->store('dokumen', 'public');
    $document->save();

    // Buat log
    DocumentLog::create([
        'document_id' => $document->id,
        'user_id' => Auth::id(),
        'event' => 'update dokumen',
        'status_sebelumnya' => $document->status,
        'status_sekarang' => $document->status,
        'keterangan' => 'File dokumen diperbarui oleh ' . (Auth::user()->role ?? 'user'),
    ]);

    return back()->with('success', 'Dokumen berhasil diperbarui.');
}
public function documentChart()
{
    // Ambil 7 hari terakhir
    $days = collect(range(6, 0))->map(function($i){
        return Carbon::today()->subDays($i)->format('Y-m-d');
    });

    // Hitung jumlah dokumen per hari
    $documentCounts = $days->map(function($day){
        return Documents::whereDate('created_at', $day)->count();
    });

    // Bisa return view khusus grafik, atau gabungkan ke admin dashboard
    return view('admin.dashboard-documents', [
        'days' => $days,
        'documentCounts' => $documentCounts
    ]);
}


}
