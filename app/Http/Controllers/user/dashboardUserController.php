<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Documents;
use App\Models\DocumentLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardUserController extends Controller
{
    /**
     * Dashboard User
     */
    public function index()
    {
        $user = Auth::user();

        // Jumlah dokumen milik user
        $myDocuments = Documents::where('user_id', $user->id)->count();

        // Jumlah "tugas aktif" = dokumen milik user yang belum expired
        $activeTasks = Documents::where('user_id', $user->id)
            ->where('tanggal_expired', '>', now())
            ->count();

        // Jumlah notifikasi user = jumlah log dokumen terkait user
        $myNotifications = DocumentLog::whereHas('document', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        // Dokumen terbaru user
        $latestDocuments = Documents::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($doc) {
                $doc->tanggal_expired = Carbon::parse($doc->tanggal_expired);
                return $doc;
            });

        // Notifikasi terbaru (ambil dari document_logs terkait user)
        $userNotifications = DocumentLog::with('document')
            ->whereHas('document', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard-user', compact(
            'myDocuments',
            'activeTasks',
            'myNotifications',
            'latestDocuments',
            'userNotifications'
        ));
    }
}
