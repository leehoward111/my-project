<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Emotion;

class UnityApiController extends Controller
{
    public function checkNewPlayer(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($validated['user_id']);
        $emotionCount = Emotion::where('user_id', $user->id)->count();
        
        return response()->json([
            'user_id' => $user->id,
            'is_new_player' => $emotionCount === 0,
            'emotion_records_count' => $emotionCount,
            'user_name' => $user->name,
            'status' => $emotionCount === 0 ? 'new_player' : 'returning_player'
        ]);
    }

    public function getCharacterData(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $latestEmotion = Emotion::where('user_id', $validated['user_id'])
                               ->latest()
                               ->first();

        if (!$latestEmotion) {
            return response()->json([
                'error' => 'No emotion data found for user',
                'requires_analysis' => true
            ], 404);
        }

        // 模擬從CharacterGenerator獲取角色數據
        $characterController = new CharacterGeneratorController();
        $characterRequest = new Request(['emotion_id' => $latestEmotion->id]);
        $characterResponse = $characterController->generateCharacter($characterRequest);
        
        return response()->json([
            'unity_ready' => true,
            'character_data' => $characterResponse->getData()->character_data,
            'emotion_context' => [
                'emotion' => $latestEmotion->emotion,
                'confidence' => $latestEmotion->confidence,
                'timestamp' => $latestEmotion->created_at
            ]
        ]);
    }

    public function receiveUnityResult(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'game_result' => 'required|array',
            'qr_requested' => 'sometimes|boolean'
        ]);

        // 處理Unity遊戲結果
        // 這裡可以儲存遊戲分數、成就等

        $response = [
            'success' => true,
            'result_processed' => true,
            'message' => '遊戲結果已處理'
        ];

        // 如果要求生成QR碼
        if ($validated['qr_requested'] ?? false) {
            $response['qr_generation_ready'] = true;
            $response['next_step'] = 'generate_qr_code';
        }

        return response()->json($response);
    }
}