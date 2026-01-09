<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Documents extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'nama_dokumen',
        'jenis_dokumen',
        'upload_dokumen',
        'nama_pic',
        'nomor_pic',
        'jabatan',
        'tanggal_expired',
        'diperingatkan_h',
        'user_id',
        'ingatkan_setelah_kadaluwarsa',
    ];

    // casting modern Laravel
    protected $casts = [
        'tanggal_expired' => 'date',
    ];

    /** Relasi ke User */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Relasi ke Log Dokumen */
    public function logs()
    {
        return $this->hasMany(DocumentLog::class, 'document_id');
    }

    /** Cek apakah dokumen sudah expired */
    public function isExpired(): bool
    {
        return $this->tanggal_expired
            ? $this->tanggal_expired->isPast()
            : false;
    }

    /** Cek apakah dokumen akan segera expired (default H-7) */
    public function isExpiringSoon(int $days = 7): bool
    {
        return $this->tanggal_expired
            ? $this->tanggal_expired->between(now(), now()->addDays($days))
            : false;
    }

    /** Accessor status dokumen (untuk Blade lebih singkat) */
    public function getStatusLabelAttribute(): string
    {
        if ($this->isExpired()) {
            return 'Expired';
        } elseif ($this->isExpiringSoon()) {
            return 'Segera Expired';
        }
        return 'Aman';
    }
}
