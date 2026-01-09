<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentLog;
use Illuminate\Support\Facades\Auth;

class DocumentLogController extends Controller
{
    /**
     * Simpan log aktivitas
     */
    public function logEvent(Request $request)
    {
        $request->validate([
            'document_id'       => 'required|exists:documents,id',
            'event'             => 'required|string|max:255',
            'keterangan'        => 'nullable|string',
            'status_sebelumnya' => 'nullable|string',
            'status_sekarang'   => 'nullable|string',
        ]);

        $log = DocumentLog::create([
            'document_id'       => $request->document_id,
            'user_id'           => Auth::id(),
            'event'             => $request->event,
            'keterangan'        => $request->keterangan,
            'status_sebelumnya' => $request->status_sebelumnya,
            'status_sekarang'   => $request->status_sekarang,
        ]);

        return response()->json([
            'message' => 'Log berhasil dicatat',
            'data'    => $log
        ], 201);
    }

    /**
     * API: Ambil log berdasarkan dokumen
     */
    public function getLogsByDocument($document_id)
    {
        $logs = DocumentLog::where('document_id', $document_id)
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Log ditemukan',
            'data'    => $logs
        ]);
    }

    /**
     * VIEW LOGS
     * - Admin : semua log
     * - User  : hanya log miliknya
     */
    public function index()
    {
        $user = Auth::user();

        // ADMIN
        if ($user->role === 'admin') {
            $logs = DocumentLog::with(['user','document'])
                ->latest()
                ->get();

            return view('admin.log', [
                'recentDocumentLogs' => $logs
            ]);
        }

        // USER
        $logs = DocumentLog::with(['user','document'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('documents.log-documents', [
            'logs' => $logs
        ]);
    }
}
