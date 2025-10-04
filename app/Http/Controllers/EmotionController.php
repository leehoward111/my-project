<?php

namespace App\Http\Controllers;

use App\Models\Emotion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmotionController extends Controller
{
    public function index(): JsonResponse
    {
        $emotions = Emotion::with('user')->latest()->get();
        return response()->json($emotions);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'emotion' => 'required|in:happy,sad,angry,neutral,surprised',
            'confidence' => 'required|numeric|between:0,1',
            'image_path' => 'nullable|string'
        ]);

        $emotion = Emotion::create($validated);
        return response()->json($emotion, 201);
    }

    public function show(Emotion $emotion): JsonResponse
    {
        return response()->json($emotion->load('user'));
    }

    public function update(Request $request, Emotion $emotion): JsonResponse
    {
        $validated = $request->validate([
            'emotion' => 'sometimes|in:happy,sad,angry,neutral,surprised',
            'confidence' => 'sometimes|numeric|between:0,1',
            'image_path' => 'nullable|string'
        ]);

        $emotion->update($validated);
        return response()->json($emotion);
    }

    public function destroy(Emotion $emotion): JsonResponse
    {
        $emotion->delete();
        return response()->json(['message' => 'Emotion deleted successfully']);
    }
}