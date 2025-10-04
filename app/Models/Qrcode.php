<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Qrcode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'image_path',   // 相對於 storage/public 的路徑，或完整 URL
        'scan_count',
        'is_active',
    ];

    // 如果有 users 表，可開啟這個關聯（否則忽略）
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
