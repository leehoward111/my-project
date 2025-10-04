<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Qrcode;

class QrGeneratorController extends Controller
{
    // Demo 期間：放寬 user_id，避免 users 沒資料就 422
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'content' => 'required|string',
            'size'    => 'sometimes|integer|min:100|max:500'
        ]);

        $size = $validated['size'] ?? 200;
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode($validated['content']);

        Qrcode::create([
            'user_id'    => $validated['user_id'],
            'content'    => $validated['content'],
            'image_path' => $qrUrl
        ]);

        return response()->json([
            'success'   => true,
            'image_url' => $qrUrl
        ], 201);
    }

    public function generateUserProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|integer'
        ]);

        $url  = url('/demo.html?user=' . $validated['user_id']);
        $qr   = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($url);

        Qrcode::create([
            'user_id'    => $validated['user_id'],
            'content'    => $url,
            'image_path' => $qr
        ]);

        return response()->json([
            'success'   => true,
            'image_url' => $qr
        ], 201);
    }
}
