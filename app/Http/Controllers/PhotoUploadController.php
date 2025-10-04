<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\File;

class PhotoUploadController extends Controller
{
    public function uploadPhoto(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'nullable|integer',
            'photo'   => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB
        ]);

        // 建立 storage 連結：php artisan storage:link
        $dir  = 'uploads/photos/' . date('Ymd');
        $path = $request->file('photo')->store($dir, 'public'); // storage/app/public/...

        $file = File::create([
            'user_id'  => $validated['user_id'] ?? null,
            'filename' => basename($path),
            'filepath' => $path, // 相對於 storage/app/public
            'filetype' => 'image',
            'filesize' => $request->file('photo')->getSize(),
        ]);

        return response()->json([
            'success'   => true,
            'message'   => '照片上傳成功',
            'file'      => $file,
            'photo_url' => asset('storage/' . $path), // <img src="...">
            'next_step' => 'video_emotion'
        ], 201);
    }
}
