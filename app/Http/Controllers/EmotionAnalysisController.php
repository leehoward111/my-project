<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Emotion;
use App\Models\File;

class EmotionAnalysisController extends Controller
{
    public function analyzeEmotion(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'file_id' => 'required|exists:files,id',
            'emotion_data' => 'required|array',
            'emotion_data.emotion' => 'required|in:happy,sad,angry,neutral,surprised',
            'emotion_data.confidence' => 'required|numeric|between:0,1',
            'emotion_data.facial_landmarks' => 'sometimes|array'
        ]);

        $file = File::find($validated['file_id']);
        
        // 儲存表情分析結果
        $emotion = Emotion::create([
            'user_id' => $validated['user_id'],
            'emotion' => $validated['emotion_data']['emotion'],
            'confidence' => $validated['emotion_data']['confidence'],
            'image_path' => $file->filepath
        ]);

        return response()->json([
            'success' => true,
            'emotion_result' => $emotion,
            'next_step' => 'character_generation',
            'message' => '表情分析完成，準備生成角色'
        ]);
    }

    public function receiveFromOpenCV(Request $request): JsonResponse
    {
        // 接收來自OpenCV/MediaPipe的分析結果
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'image_path' => 'required|string',
            'emotions' => 'required|array',
            'emotions.*.emotion' => 'required|string',
            'emotions.*.confidence' => 'required|numeric|between:0,1',
            'facial_landmarks' => 'sometimes|array'
        ]);

        // 找到信心度最高的表情
        $topEmotion = collect($validated['emotions'])->sortByDesc('confidence')->first();

        $emotion = Emotion::create([
            'user_id' => $validated['user_id'],
            'emotion' => $topEmotion['emotion'],
            'confidence' => $topEmotion['confidence'],
            'image_path' => $validated['image_path']
        ]);

        return response()->json([
            'success' => true,
            'emotion_id' => $emotion->id,
            'detected_emotion' => $topEmotion['emotion'],
            'confidence' => $topEmotion['confidence'],
            'status' => 'analysis_complete'
        ]);
    }
}