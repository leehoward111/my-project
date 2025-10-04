<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Emotion;
use App\Models\User;

class CharacterGeneratorController extends Controller
{
    public function generateCharacter(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'emotion_id' => 'required|exists:emotions,id',
            'user_preferences' => 'sometimes|array'
        ]);

        $emotion = Emotion::with('user')->find($validated['emotion_id']);
        
        // 根據表情生成角色設定
        $character = $this->createCharacterFromEmotion($emotion);

        return response()->json([
            'success' => true,
            'character_data' => $character,
            'emotion_context' => [
                'detected_emotion' => $emotion->emotion,
                'confidence' => $emotion->confidence
            ],
            'unity_ready' => true,
            'message' => '角色生成完成，可以傳送給Unity'
        ]);
    }

    private function createCharacterFromEmotion($emotion): array
    {
        $templates = [
            'happy' => [
                'personality' => 'cheerful',
                'color_scheme' => 'bright',
                'energy_level' => 'high',
                'special_abilities' => ['joy_boost', 'healing']
            ],
            'sad' => [
                'personality' => 'melancholic',
                'color_scheme' => 'blue_tones',
                'energy_level' => 'low',
                'special_abilities' => ['empathy', 'reflection']
            ],
            'angry' => [
                'personality' => 'fierce',
                'color_scheme' => 'red_tones',
                'energy_level' => 'very_high',
                'special_abilities' => ['strength_boost', 'intimidation']
            ],
            'neutral' => [
                'personality' => 'balanced',
                'color_scheme' => 'neutral_tones',
                'energy_level' => 'medium',
                'special_abilities' => ['adaptability', 'stability']
            ],
            'surprised' => [
                'personality' => 'curious',
                'color_scheme' => 'vibrant',
                'energy_level' => 'high',
                'special_abilities' => ['discovery', 'quick_reflexes']
            ]
        ];

        $template = $templates[$emotion->emotion] ?? $templates['neutral'];
        
        return [
            'character_id' => 'char_' . $emotion->id . '_' . time(),
            'user_id' => $emotion->user_id,
            'base_emotion' => $emotion->emotion,
            'confidence_level' => $emotion->confidence,
            'traits' => $template,
            'created_for_unity' => true,
            'creation_timestamp' => now()
        ];
    }
}