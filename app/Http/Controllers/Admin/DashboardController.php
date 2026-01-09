<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Documents;
use App\Models\DocumentLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index()
{
    $totalUsers = User::count();
    $totalAdmins = User::where('role', 'admin')->count();
    $totalRegularUsers = User::where('role', 'user')->count();
    $totalDocuments = Documents::count();
    $alertThreshold = 7;
    $expiringDocuments = Documents::where('tanggal_expired', '<=', Carbon::now()->addDays($alertThreshold))->count();

    $recentDocuments = Documents::latest()->take(5)->get();
    $recentDocuments->transform(function ($doc) {
        $doc->tanggal_expired = Carbon::parse($doc->tanggal_expired);
        return $doc;
    });

    $recentDocumentLogs = DocumentLog::with('document')->latest()->limit(10)->get();

     $now = Carbon::now();
    $bahaya = Documents::where('tanggal_expired', '<', $now)->count();
    $waspada = Documents::whereBetween('tanggal_expired', [$now, $now->copy()->addDays(14)])->count();
    $good = Documents::where('tanggal_expired', '>', $now->copy()->addDays(30))->count();

    // =============================
    // Grafik dokumen per hari 7 hari terakhir
    // =============================
    $days = collect(range(6, 0))->map(fn($i) => Carbon::today()->subDays($i)->format('Y-m-d'));
    $documentCounts = $days->map(fn($day) => Documents::whereDate('created_at', $day)->count());

    return view('admin.dashboard', compact(
        'totalUsers',
        'totalAdmins',
        'totalRegularUsers',
        'totalDocuments',
        'expiringDocuments',
        'recentDocuments',
        'alertThreshold',
        'recentDocumentLogs',
        'days',
        'documentCounts',
        'bahaya',
        'waspada',
        'good'
    ));
}


}
