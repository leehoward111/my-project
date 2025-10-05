<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FalAiController extends Controller
{
    private $apiKey;
    private $baseUrl;
    private $defaultModel;

    public function __construct()
    {
        $this->apiKey = env('FAL_AI_API_KEY');
        $this->baseUrl = env('FAL_AI_BASE_URL', 'https://fal.run');
        $this->defaultModel = env('FAL_AI_DEFAULT_MODEL', 'fal-ai/ideogram/character');
    }

    /**
     * 測試 fal.ai 連接
     * GET /api/fal/test
     */
    public function test()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post("{$this->baseUrl}/{$this->defaultModel}", [
                'prompt' => 'A simple test character, minimalist style',
                'num_images' => 1,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'message' => 'fal.ai API 連接正常',
                    'test_image' => $data['images'][0]['url'] ?? null,
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'API 回應異常',
                'status' => $response->status(),
                'body' => $response->body(),
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'fal.ai 連接失敗：' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 列出可用的模型
     * GET /api/fal/models
     */
    public function listModels()
    {
        $models = [
            [
                'id' => 'fal-ai/ideogram/character',
                'name' => 'Ideogram Character',
                'description' => '專業角色生成，支援圖生圖（推薦）',
                'speed' => 'fast',
                'quality' => 'high',
                'recommended' => true,
                'supports_image_to_image' => true,
            ],
            [
                'id' => 'fal-ai/flux/dev',
                'name' => 'FLUX.1 [dev]',
                'description' => '高品質、快速的圖像生成',
                'speed' => 'fast',
                'quality' => 'high',
                'recommended' => false,
                'supports_image_to_image' => false,
            ],
            [
                'id' => 'fal-ai/flux/schnell',
                'name' => 'FLUX.1 [schnell]',
                'description' => '超快速生成，適合即時預覽',
                'speed' => 'fastest',
                'quality' => 'medium',
                'recommended' => false,
                'supports_image_to_image' => false,
            ],
        ];

        return response()->json([
            'success' => true,
            'models' => $models,
            'current_model' => $this->defaultModel,
        ]);
    }

    /**
     * 一般風格圖片生成
     * POST /api/fal/generate-style-image
     */
    public function generateStyleImage(Request $request)
    {
        $validated = $request->validate([
            'prompt' => 'required|string|max:2000',
            'negative_prompt' => 'nullable|string|max:1000',
            'model' => 'nullable|string',
            'num_images' => 'nullable|integer|min:1|max:4',
            'image_url' => 'nullable|url', // 支援圖生圖
            'strength' => 'nullable|numeric|min:0|max:1', // 圖生圖強度
        ]);

        $model = $validated['model'] ?? $this->defaultModel;
        $numImages = $validated['num_images'] ?? 1;

        try {
            $payload = [
                'prompt' => $validated['prompt'],
                'num_images' => $numImages,
            ];

            // 如果有提供圖片 URL，則使用圖生圖模式
            if (!empty($validated['image_url'])) {
                $payload['image_url'] = $validated['image_url'];
                $payload['strength'] = $validated['strength'] ?? 0.75; // 預設強度 0.75
            }

            if (!empty($validated['negative_prompt'])) {
                $payload['negative_prompt'] = $validated['negative_prompt'];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post("{$this->baseUrl}/{$model}", $payload);

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'success' => true,
                    'images' => $data['images'] ?? [],
                    'prompt' => $validated['prompt'],
                    'model' => $model,
                    'seed' => $data['seed'] ?? null,
                    'is_image_to_image' => !empty($validated['image_url']),
                ]);
            }

            throw new \Exception('API 回應錯誤：' . $response->body());

        } catch (\Exception $e) {
            Log::error('fal.ai 生圖失敗', [
                'error' => $e->getMessage(),
                'prompt' => $validated['prompt'] ?? '',
            ]);

            return response()->json([
                'success' => false,
                'error' => '圖片生成失敗：' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 基於角色檔案生成風格形象
     * POST /api/fal/generate-character-image
     */
    public function generateCharacterImage(Request $request)
    {
        $validated = $request->validate([
            'character_archetype' => 'required|string',
            'personality_type' => 'nullable|string',
            'style_keywords' => 'nullable|array',
            'color_palette' => 'nullable|array',
            'accessories' => 'nullable|array',
            'emotion' => 'nullable|string',
            'model' => 'nullable|string',
            'image_url' => 'nullable|url', // 支援圖生圖
            'strength' => 'nullable|numeric|min:0|max:1',
        ]);

        // 建構 prompt
        $prompt = $this->buildCharacterPrompt($validated);
        $model = $validated['model'] ?? $this->defaultModel;

        try {
            $payload = [
                'prompt' => $prompt,
                'num_images' => 1,
            ];

            // 如果有提供圖片 URL，則使用圖生圖模式
            if (!empty($validated['image_url'])) {
                $payload['image_url'] = $validated['image_url'];
                $payload['strength'] = $validated['strength'] ?? 0.75;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post("{$this->baseUrl}/{$model}", $payload);

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'success' => true,
                    'image_url' => $data['images'][0]['url'] ?? null,
                    'prompt' => $prompt,
                    'character_data' => $validated,
                    'seed' => $data['seed'] ?? null,
                    'is_image_to_image' => !empty($validated['image_url']),
                ]);
            }

            throw new \Exception('API 回應錯誤：' . $response->body());

        } catch (\Exception $e) {
            Log::error('fal.ai 角色圖生成失敗', [
                'error' => $e->getMessage(),
                'archetype' => $validated['character_archetype'] ?? '',
            ]);

            return response()->json([
                'success' => false,
                'error' => '角色圖片生成失敗：' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 建構角色 prompt
     */
    private function buildCharacterPrompt(array $data): string
    {
        $archetype = $data['character_archetype'] ?? '探索者';
        $personality = $data['personality_type'] ?? '';
        $keywords = $data['style_keywords'] ?? [];
        $emotion = $data['emotion'] ?? 'neutral';
        $accessories = $data['accessories'] ?? [];

        // 情緒對應描述
        $emotionMap = [
            'happy' => 'cheerful and smiling, bright expression',
            'sad' => 'melancholic and thoughtful, gentle expression',
            'angry' => 'intense and determined, strong expression',
            'neutral' => 'calm and composed, natural expression',
            'surprised' => 'curious and excited, amazed expression',
        ];

        // 關鍵字中英對照
        $keywordMap = [
            '清新' => 'fresh',
            '輕盈' => 'lightweight',
            '機能' => 'functional',
            '好奇' => 'curious',
            '自發' => 'spontaneous',
            '友善' => 'friendly',
            '明亮' => 'bright',
            '休閒' => 'casual',
            '都會' => 'urban',
        ];

        $emotionDesc = $emotionMap[$emotion] ?? 'natural expression';

        $prompt = "A stylish portrait photograph of a person embodying the '{$archetype}' archetype, {$emotionDesc}";

        // 添加風格關鍵字
        if (!empty($keywords)) {
            $styleWords = array_map(fn($k) => $keywordMap[$k] ?? $k, $keywords);
            $prompt .= ', fashion style: ' . implode(', ', $styleWords);
        }

        // 添加配飾
        if (!empty($accessories)) {
            $accessoryList = array_values($accessories);
            $prompt .= ', wearing: ' . implode(', ', $accessoryList);
        }

        // 添加品質描述
        $prompt .= ', professional fashion photography, studio lighting, high quality, detailed, 4k, modern aesthetic';

        return $prompt;
    }
}