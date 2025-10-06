<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generateProfilePdf(Request $request)
    {
        $data = $request->all();

        return view('pdf.profile-html', [
            'profile_id' => 'STYLE_' . time(),
            'creation_date' => now()->format('Y-m-d H:i:s'),
            'gender' => $data['gender'] ?? '未指定',
            'character_data' => $data['character_data'] ?? [],
            'emotion_data' => $data['emotion_data'] ?? null,
            'accessory_data' => $data['accessory_data'] ?? null,
            'style_image_url' => $data['style_image_url'] ?? null,
            'uploaded_photo_url' => $data['uploaded_photo_url'] ?? null,
        ]);
    }
}