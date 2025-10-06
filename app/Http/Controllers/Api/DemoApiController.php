<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
            'video_emotion' => $this->videoEmotion(),
            'accessory_matching' => $this->accessoryMatching($request->input('emotion', 'happy')),
            'character_analysis' => $this->characterAnalysis($request->input('emotion', 'happy')),
            'generate_style_image' => $this->generateStyleImage($request),
            default => response()->json(['success' => false, 'error' => 'Unknown step'], 400),
        };
    }

    /**
     * 影片情緒偵測 - 隨機生成 4 個情緒點
     */
    private function videoEmotion()
    {
        $possibleEmotions = ['happy', 'neutral', 'sad'];
        $emotionSequence = [];
        $chineseSequence = [];

        $emotionMap = [
            'happy' => '正向',
            'neutral' => '中性',
            'sad' => '負向'
        ];

        // 生成 4 個隨機情緒點
        for ($i = 0; $i < 4; $i++) {
            $emotion = $possibleEmotions[array_rand($possibleEmotions)];
            $emotionSequence[] = [
                'time' => ($i * 15) . 's',
                'emotion' => $emotion,
                'confidence' => rand(75, 95) / 100
            ];
            $chineseSequence[] = $emotionMap[$emotion];
        }

        // 計算主導情緒
        $emotionCounts = array_count_values(array_column($emotionSequence, 'emotion'));
        arsort($emotionCounts);
        $dominantEmotion = key($emotionCounts);

        return response()->json([
            'success' => true,
            'emotion_sequence' => $emotionSequence,
            'chinese_sequence' => $chineseSequence,
            'dominant_emotion' => $dominantEmotion,
            'analysis_summary' => $this->getEmotionSummary($dominantEmotion)
        ]);
    }

    private function getEmotionSummary($emotion)
    {
        $summaries = [
            'happy' => '觀看過程中表現出積極正向的情緒反應，對時尚元素展現高度興趣',
            'neutral' => '整體情緒保持平穩，展現理性且客觀的觀察態度',
            'sad' => '情緒較為內斂沉穩，偏好低調優雅的風格表達'
        ];
        return $summaries[$emotion] ?? '情緒分析完成';
    }

    /**
     * 配飾搭配 - 從資料庫的 81 筆公式中查詢
     */
    private function accessoryMatching($emotion)
    {
        $emotionMap = [
            'happy' => '正向',
            'neutral' => '中性',
            'sad' => '負向'
        ];

        // 生成 4 個隨機情緒序列
        $sequence = [];
        for ($i = 0; $i < 4; $i++) {
            $randomEmotion = ['happy', 'neutral', 'sad'][array_rand(['happy', 'neutral', 'sad'])];
            $sequence[] = $emotionMap[$randomEmotion];
        }

        $sequenceStr = implode('、', $sequence);

        // 從資料庫查詢
        $outfit = DB::table('outfit_formulas')
            ->where('emotion_sequence', $sequenceStr)
            ->first();

        if ($outfit) {
            return response()->json([
                'success' => true,
                'emotion_sequence' => $sequenceStr,
                'accessories' => [
                    'top' => $outfit->top,
                    'bottom' => $outfit->bottom,
                    'accessory' => $outfit->accessory
                ],
                'styling_concept' => $outfit->category,
                'explanation' => $outfit->explanation
            ]);
        }

        // 找不到完全匹配時，根據類型隨機選擇
        return $this->getDefaultOutfit($emotion);
    }

    private function getDefaultOutfit($emotion)
    {
        $category = $emotion === 'happy' ? 'Positive' : ($emotion === 'sad' ? 'Negative' : 'Neutral');

        $outfit = DB::table('outfit_formulas')
            ->where('category', $category)
            ->inRandomOrder()
            ->first();

        if ($outfit) {
            return response()->json([
                'success' => true,
                'emotion_sequence' => $outfit->emotion_sequence,
                'accessories' => [
                    'top' => $outfit->top,
                    'bottom' => $outfit->bottom,
                    'accessory' => $outfit->accessory
                ],
                'styling_concept' => $outfit->category,
                'explanation' => $outfit->explanation
            ]);
        }

        // 最終預設值
        return response()->json([
            'success' => true,
            'accessories' => [
                'top' => '白T-shirt',
                'bottom' => '藍色牛仔褲',
                'accessory' => '後背包'
            ],
            'styling_concept' => 'Casual',
            'explanation' => '基本休閒搭配'
        ]);
    }

    /**
     * 角色性格分析
     */
    private function characterAnalysis($emotion)
    {
        $characters = [
            'happy' => [
                'character_archetype' => '陽光探險家',
                'personality_type' => 'ENFP - 熱情洋溢的活動家',
                'traits' => ['樂觀開朗', '充滿好奇心', '善於社交', '富有創意'],
                'style_keywords' => ['活力四射', '色彩繽紛', '休閒舒適', '年輕時尚'],
                'color_palette' => ['#FFD700', '#FF6B6B', '#4ECDC4', '#95E1D3']
            ],
            'neutral' => [
                'character_archetype' => '理性建築師',
                'personality_type' => 'INTJ - 深思熟慮的策略家',
                'traits' => ['冷靜理性', '邏輯清晰', '追求效率', '獨立思考'],
                'style_keywords' => ['簡約俐落', '質感優先', '極簡主義', '都會風格'],
                'color_palette' => ['#2C3E50', '#7F8C8D', '#BDC3C7', '#ECF0F1']
            ],
            'sad' => [
                'character_archetype' => '內斂詩人',
                'personality_type' => 'INFP - 理想主義的調停者',
                'traits' => ['情感細膩', '善於觀察', '內斂深沉', '追求意義'],
                'style_keywords' => ['低調優雅', '內斂沉穩', '文藝氣息', '復古質感'],
                'color_palette' => ['#34495E', '#5D4E6D', '#8B7E8B', '#A9B2AC']
            ]
        ];

        $profile = $characters[$emotion] ?? $characters['neutral'];

        return response()->json([
            'success' => true,
            'character_profile' => $profile
        ]);
    }

    /**
     * 使用 fal.ai 生成風格形象圖（圖生圖）
     */
    private function generateStyleImage(Request $request)
    {
        $characterData = $request->input('character_data', []);
        $emotion = $request->input('emotion', 'happy');
        $accessories = $request->input('accessories', []);
        $imageUrl = $request->input('image_url');
        $strength = $request->input('strength', 0.75);
        $gender = $request->input('gender', 'female');

        if (empty($imageUrl)) {
            return response()->json([
                'success' => false,
                'error' => '此功能需要提供參考圖片。請先上傳照片後再生成風格形象。',
                'required_field' => 'image_url',
            ], 400);
        }

        $prompt = $this->buildPrompt($characterData, $emotion, $accessories, $gender);

        try {
            Log::info('開始生成風格圖片', [
                'prompt' => $prompt,
                'model' => $this->defaultModel,
                'source_image' => $imageUrl,
                'gender' => $gender,
            ]);

            $payload = [
                'prompt' => $prompt,
                'reference_image_urls' => [$imageUrl],
                'num_images' => 1,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->falApiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post("{$this->falBaseUrl}/{$this->defaultModel}", $payload);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('風格圖片生成成功', [
                    'image_url' => $data['images'][0]['url'] ?? null,
                ]);

                return response()->json([
                    'success' => true,
                    'image_url' => $data['images'][0]['url'] ?? null,
                    'prompt' => $prompt,
                    'seed' => $data['seed'] ?? null,
                    'model' => $this->defaultModel,
                    'message' => '風格形象圖生成成功',
                ]);
            }

            Log::error('fal.ai API 錯誤', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new \Exception('API 回應錯誤：' . $response->body());

        } catch (\Exception $e) {
            Log::error('圖片生成失敗', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => '圖片生成失敗：' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 建構 AI prompt，加入性別資訊
     */
    private function buildPrompt($characterData, $emotion, $accessories, $gender): string
    {
        $archetype = $characterData['character_archetype'] ?? '探索者';
        $keywords = $characterData['style_keywords'] ?? ['清新', '輕盈', '機能'];

        $genderDesc = $gender === 'male' ? 'a stylish man' : 'a stylish woman';

        $emotionMap = [
            'happy' => 'cheerful and smiling, bright and positive expression',
            'sad' => 'melancholic and thoughtful, gentle expression',
            'neutral' => 'calm and composed, natural expression',
        ];

        $emotionDesc = $emotionMap[$emotion] ?? 'natural expression';

        $prompt = "A professional fashion portrait photograph of {$genderDesc} embodying the '{$archetype}' personality archetype";
        $prompt .= ", with {$emotionDesc}";
        $prompt .= ", fashion style: " . implode(', ', $keywords);

        if (!empty($accessories)) {
            $top = $accessories['top'] ?? '';
            $bottom = $accessories['bottom'] ?? '';
            $accessory = $accessories['accessory'] ?? '';
            $prompt .= ", wearing: {$top}, {$bottom}, with {$accessory}";
        }

        $prompt .= ", professional fashion photography, studio lighting, high quality, detailed, modern aesthetic";

        return $prompt;
    }
}