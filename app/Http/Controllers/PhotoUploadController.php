<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotoUploadController extends Controller
{
    public function uploadPhoto(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'required|image|max:10240',
            'gender' => 'required|in:male,female',
        ]);

        $photo = $request->file('photo');
        $gender = $validated['gender'];

        // 儲存檔案
        $filename = 'photo_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path('uploads/photos'), $filename);

        $localUrl = url('uploads/photos/' . $filename);

        // 轉換成 Base64
        $photoPath = public_path('uploads/photos/' . $filename);
        $imageData = file_get_contents($photoPath);
        $base64 = 'data:image/' . $photo->getClientOriginalExtension() . ';base64,' . base64_encode($imageData);

        return response()->json([
            'success' => true,
            'photo_url' => $localUrl,
            'photo_base64' => $base64,  // 給 API 用
            'gender' => $gender,
        ]);
    }
}