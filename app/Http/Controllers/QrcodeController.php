<?php

namespace App\Http\Controllers;

use App\Models\Qrcode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QrcodeController extends Controller
{
    public function index(): JsonResponse
    {
        $qrcodes = Qrcode::with('user')->latest()->get();
        return response()->json($qrcodes);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'image_path' => 'required|string',
            'scan_count' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean'
        ]);

        $qrcode = Qrcode::create($validated);
        return response()->json($qrcode, 201);
    }

    public function show(Qrcode $qrcode): JsonResponse
    {
        return response()->json($qrcode->load('user'));
    }

    public function update(Request $request, Qrcode $qrcode): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'sometimes|string',
            'image_path' => 'sometimes|string',
            'scan_count' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean'
        ]);

        $qrcode->update($validated);
        return response()->json($qrcode);
    }

    public function destroy(Qrcode $qrcode): JsonResponse
    {
        $qrcode->delete();
        return response()->json(['message' => 'QR code deleted successfully']);
    }
}