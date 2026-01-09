<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'user_id',
        'event',
        'status_sebelumnya',
        'status_sekarang',
        'keterangan',
    ];

    public function document()
    {
        return $this->belongsTo(Documents::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}