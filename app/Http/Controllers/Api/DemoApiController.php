<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DemoApiController extends Controller
{
    private $falApiKey;
    private $falBaseUrl;
    private $defaultModel;

    public function __construct()
    {
        $this->falApiKey = env('FAL_AI_API_KEY');
        $this->falBaseUrl = env('FAL_AI_BASE_URL', 'https://fal.run');
        $this->defaultModel = env('FAL_AI_DEFAULT_MODEL', 'fal-ai/ideogram/character');
    }

    public function handle(Request $request)
    {
        $step = $request->input('step');

        return match ($step) {
            'entry_qr' => response()->json([
                'success' => true,
                'qr_url' => url('/placeholder-qr.png'),
                'message' => '請用手機掃描開始體驗'
            ]),

            'photo_upload' => response()->json([
                'success' => true,
                'message' => '已接收照片（示範）'
            ]),

            'video_emotion' => response()->json([
                'success' => true,
                'dominant_emotion' => 'happy',
                'emotion_sequence' => [
                    ['time' => '00:05', 'emotion' => 'happy', 'confidence' => 0.82],
                    ['time' => '00:12', 'emotion' => 'surprised', 'confidence' => 0.76],
                    ['time' => '00:21', 'emotion' => 'neutral', 'confidence' => 0.64],
                ],
                'analysis_summary' => '整體偏正向、接受新奇元素',
            ]),

            'accessory_matching' => response()->json([
                'success' => true,
                'accessories' => [
                    'hat' => '棒球帽（淺灰）',
                    'glasses' => '輕薄金屬框',
                    'jewelry' => '簡約銀戒',
                    'bag' => '小型斜背包',
                ],
                'styling_concept' => '明亮休閒 × 都會感',
            ]),

            'character_analysis' => response()->json([
                'success' => true,
                'overall_assessment' => '偏外向、喜歡嘗試新風格',
                'character_profile' => [
                    'character_archetype' => '探索者',
                    'personality_type' => 'ENFP',
                    'traits' => ['好奇', '自發', '友善'],
                    'style_keywords' => ['清新', '輕盈', '機能'],
                    'color_palette' => ['#7c3aed', '#06b6d4', '#f59e0b'],
                ],
            ]),

            'output_profile' => response()->json([
                'success' => true,
                'character_profile' => [
                    'profile_id' => 'STYLE_' . now()->timestamp,
                    'style_recommendations' => [
                        '選擇輕盈布料與中性色作為日常基底',
                        '以亮色小配件提升活力感',
                        '維持簡約輪廓，增加金屬細節',
                    ],
                    'personality_insights' => [
                        '傾向在社交場合展現友善與好奇',
                        '對新穎事物具高度接受度',
                        '偏好機能與美感兼具的穿搭選擇',
                    ],
                ],
            ]),

            // 生成風格形象圖（支援文生圖和圖生圖）
            'generate_style_image' => $this->generateStyleImage($request),

            default => response()->json(['success' => false, 'error' => 'Unknown step'], 400),
        };
    }

    /**
     * 使用 fal.ai 生成風格形象圖
     * 支援文生圖和圖生圖兩種模式
     */
    private function generateStyleImage(Request $request)
    {
        $characterData = $request->input('character_data', []);
        $emotion = $request->input('emotion', 'happy');
        $accessories = $request->input('accessories', []);
        $imageUrl = $request->input('image_url'); // 圖生圖來源圖片
        $strength = $request->input('strength', 0.75); // 圖生圖強度

        // 建構 prompt
        $prompt = $this->buildPrompt($characterData, $emotion, $accessories);

        try {
            Log::info('開始生成風格圖片', [
                'prompt' => $prompt,
                'model' => $this->defaultModel,
                'is_image_to_image' => !empty($imageUrl),
            ]);

            $payload = [
                'prompt' => $prompt,
                'num_images' => 1,
            ];

            // 如果有提供圖片 URL，則使用圖生圖模式
            if (!empty($imageUrl)) {
                $payload['image_url'] = $imageUrl;
                $payload['strength'] = $strength;
                Log::info('使用圖生圖模式', [
                    'source_image' => $imageUrl,
                    'strength' => $strength,
                ]);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->falApiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post("{$this->falBaseUrl}/{$this->defaultModel}", $payload);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('風格圖片生成成功', [
                    'image_url' => $data['images'][0]['url'] ?? null,
                    'seed' => $data['seed'] ?? null,
                ]);

                return response()->json([
                    'success' => true,
                    'image_url' => $data['images'][0]['url'] ?? null,
                    'prompt' => $prompt,
                    'seed' => $data['seed'] ?? null,
                    'model' => $this->defaultModel,
                    'is_image_to_image' => !empty($imageUrl),
                    'message' => '風格形象圖生成成功',
                ]);
            }

            Log::error('fal.ai API 錯誤', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new \Exception('API 回應錯誤：' . $response->status());

        } catch (\Exception $e) {
            Log::error('圖片生成失敗', [
                'error' => $e->getMessage(),
                'prompt' => $prompt ?? '',
            ]);

            return response()->json([
                'success' => false,
                'error' => '圖片生成失敗：' . $e->getMessage(),
                'prompt' => $prompt ?? '',
            ], 500);
        }
    }

    /**
     * 建構 AI 圖片生成的 prompt
     */
    private function buildPrompt($characterData, $emotion, $accessories): string
    {
        $archetype = $characterData['character_archetype'] ?? '探索者';
        $keywords = $characterData['style_keywords'] ?? ['清新', '輕盈', '機能'];

        // 情緒對應描述
        $emotionMap = [
            'happy' => 'cheerful and smiling, bright and positive expression',
            'sad' => 'melancholic and thoughtful, gentle and soft expression',
            'angry' => 'intense and determined, strong and powerful expression',
            'neutral' => 'calm and composed, natural and balanced expression',
            'surprised' => 'curious and excited, amazed and wonder expression',
        ];

        // 關鍵字中英對照
        $keywordMap = [
            '清新' => 'fresh and clean',
            '輕盈' => 'lightweight and airy',
            '機能' => 'functional and practical',
            '好奇' => 'curious',
            '自發' => 'spontaneous',
            '友善' => 'friendly',
            '明亮' => 'bright',
            '休閒' => 'casual',
            '都會' => 'urban and modern',
        ];

        $emotionDesc = $emotionMap[$emotion] ?? 'natural expression';

        // 轉換風格關鍵字
        $styleWords = [];
        foreach ($keywords as $word) {
            $styleWords[] = $keywordMap[$word] ?? $word;
        }

        // 組合 prompt
        $prompt = "A professional fashion portrait photograph of a stylish person embodying the '{$archetype}' personality archetype";
        $prompt .= ", with {$emotionDesc}";
        $prompt .= ", fashion style: " . implode(', ', $styleWords);

        // 添加配飾描述
        if (!empty($accessories)) {
            $accessoryList = array_values($accessories);
            $prompt .= ", wearing: " . implode(', ', $accessoryList);
        }

        // 添加攝影品質描述
        $prompt .= ", professional fashion photography, studio lighting, soft shadows, high quality, detailed, 4k, modern aesthetic, contemporary style";

        return $prompt;
    }
}