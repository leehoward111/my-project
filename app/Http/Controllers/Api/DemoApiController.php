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
     * 影片情緒偵測 - 隨機生成 多 個情緒點
     */
    private function videoEmotion()
    {
        $videoNum = request()->input('video_number', 1);

        // 影片 1: 飛機墜毀 - 多組可能的情緒反應
        $video1Patterns = [
            [
                'emotions' => [
                    ['time' => '0s', 'emotion' => 'neutral', 'confidence' => 0.83],
                    ['time' => '15s', 'emotion' => 'surprised', 'confidence' => 0.91],
                    ['time' => '30s', 'emotion' => 'focused', 'confidence' => 0.88],
                    ['time' => '45s', 'emotion' => 'thoughtful', 'confidence' => 0.85],
                ],
                'dominant' => 'surprised',
                'description' => '驚訝反應型 - 對突發狀況展現高度警覺與專注'
            ],
            [
                'emotions' => [
                    ['time' => '0s', 'emotion' => 'curious', 'confidence' => 0.80],
                    ['time' => '15s', 'emotion' => 'focused', 'confidence' => 0.92],
                    ['time' => '30s', 'emotion' => 'focused', 'confidence' => 0.89],
                    ['time' => '45s', 'emotion' => 'calm', 'confidence' => 0.84],
                ],
                'dominant' => 'focused',
                'description' => '冷靜分析型 - 保持理性思考與沉著應對'
            ],
            [
                'emotions' => [
                    ['time' => '0s', 'emotion' => 'neutral', 'confidence' => 0.82],
                    ['time' => '15s', 'emotion' => 'surprised', 'confidence' => 0.90],
                    ['time' => '30s', 'emotion' => 'thoughtful', 'confidence' => 0.87],
                    ['time' => '45s', 'emotion' => 'focused', 'confidence' => 0.86],
                ],
                'dominant' => 'thoughtful',
                'description' => '深度思考型 - 從事件中進行深刻反思'
            ],
            [
                'emotions' => [
                    ['time' => '0s', 'emotion' => 'relaxed', 'confidence' => 0.81],
                    ['time' => '15s', 'emotion' => 'surprised', 'confidence' => 0.93],
                    ['time' => '30s', 'emotion' => 'surprised', 'confidence' => 0.88],
                    ['time' => '45s', 'emotion' => 'neutral', 'confidence' => 0.83],
                ],
                'dominant' => 'surprised',
                'description' => '震撼感受型 - 對戲劇性畫面產生強烈印象'
            ],
        ];

        // 影片 2: 藍鯨跳躍 - 多組可能的情緒反應
        $video2Patterns = [
            [
                'emotions' => [
                    ['time' => '0s', 'emotion' => 'curious', 'confidence' => 0.86],
                    ['time' => '15s', 'emotion' => 'excited', 'confidence' => 0.94],
                    ['time' => '30s', 'emotion' => 'inspired', 'confidence' => 0.91],
                    ['time' => '45s', 'emotion' => 'happy', 'confidence' => 0.89],
                ],
                'dominant' => 'excited',
                'description' => '熱情感動型 - 對自然奇觀展現強烈情感共鳴'
            ],
            [
                'emotions' => [
                    ['time' => '0s', 'emotion' => 'neutral', 'confidence' => 0.84],
                    ['time' => '15s', 'emotion' => 'surprised', 'confidence' => 0.92],
                    ['time' => '30s', 'emotion' => 'inspired', 'confidence' => 0.88],
                    ['time' => '45s', 'emotion' => 'calm', 'confidence' => 0.85],
                ],
                'dominant' => 'inspired',
                'description' => '靈感啟發型 - 從自然美景中獲得啟發與平靜'
            ],
            [
                'emotions' => [
                    ['time' => '0s', 'emotion' => 'relaxed', 'confidence' => 0.83],
                    ['time' => '15s', 'emotion' => 'curious', 'confidence' => 0.87],
                    ['time' => '30s', 'emotion' => 'excited', 'confidence' => 0.90],
                    ['time' => '45s', 'emotion' => 'inspired', 'confidence' => 0.88],
                ],
                'dominant' => 'inspired',
                'description' => '漸進感動型 - 情緒層層遞進至深受感動'
            ],
            [
                'emotions' => [
                    ['time' => '0s', 'emotion' => 'calm', 'confidence' => 0.85],
                    ['time' => '15s', 'emotion' => 'surprised', 'confidence' => 0.91],
                    ['time' => '30s', 'emotion' => 'happy', 'confidence' => 0.89],
                    ['time' => '45s', 'emotion' => 'relaxed', 'confidence' => 0.86],
                ],
                'dominant' => 'happy',
                'description' => '愉悅觀賞型 - 享受自然之美帶來的愉悅感'
            ],
            [
                'emotions' => [
                    ['time' => '0s', 'emotion' => 'neutral', 'confidence' => 0.82],
                    ['time' => '15s', 'emotion' => 'curious', 'confidence' => 0.88],
                    ['time' => '30s', 'emotion' => 'surprised', 'confidence' => 0.93],
                    ['time' => '45s', 'emotion' => 'excited', 'confidence' => 0.90],
                ],
                'dominant' => 'surprised',
                'description' => '驚嘆欣賞型 - 對生命力量展現驚嘆與讚賞'
            ],
        ];

        // 根據影片編號選擇對應的情緒組，並隨機選一組
        $patterns = $videoNum == 1 ? $video1Patterns : $video2Patterns;
        $template = $patterns[array_rand($patterns)];

        $emotionMap = [
            'happy' => '正向',
            'excited' => '正向',
            'surprised' => '正向',
            'curious' => '正向',
            'neutral' => '中性',
            'focused' => '中性',
            'thoughtful' => '中性',
            'relaxed' => '中性',
            'calm' => '中性',
            'inspired' => '正向'
        ];

        $chineseSequence = array_map(function ($item) use ($emotionMap) {
            return $emotionMap[$item['emotion']];
        }, $template['emotions']);

        return response()->json([
            'success' => true,
            'emotion_sequence' => $template['emotions'],
            'chinese_sequence' => $chineseSequence,
            'dominant_emotion' => $template['dominant'],
            'analysis_summary' => $this->getEmotionSummary($template['dominant']),
            'video_number' => $videoNum,
            'emotion_profile' => $template['description']
        ]);
    }

    private function getEmotionSummary($emotion)
    {
        $summaries = [
            'happy' => '觀看過程中表現出積極正向的情緒反應，對時尚元素展現高度興趣',
            'excited' => '展現強烈的熱情與活力，對新穎設計充滿期待感',
            'surprised' => '對創新元素表現出驚喜反應，具有開放接納的心態',
            'curious' => '保持高度好奇心，願意探索不同風格的可能性',
            'neutral' => '整體情緒保持平穩，展現理性且客觀的觀察態度',
            'focused' => '專注投入於影片內容，展現深度思考與分析能力',
            'thoughtful' => '呈現深思熟慮的狀態，偏好經過仔細考量的風格選擇',
            'relaxed' => '保持輕鬆自在的觀看狀態，展現從容優雅的氣質',
            'calm' => '維持沉穩平和的心境，傾向簡約內斂的美學',
            'inspired' => '受到啟發與激勵，展現創造力與想像力'
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
            'excited' => '正向',
            'surprised' => '正向',
            'curious' => '正向',
            'neutral' => '中性',
            'focused' => '中性',
            'thoughtful' => '中性',
            'relaxed' => '中性',
            'calm' => '中性',
            'inspired' => '正向'
        ];

        $possibleEmotions = array_keys($emotionMap);
        $sequence = [];

        for ($i = 0; $i < 4; $i++) {
            $randomEmotion = $possibleEmotions[array_rand($possibleEmotions)];
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
        // 將情緒分類
        $emotionCategory = $this->getEmotionCategory($emotion);

        // 正向情緒 - 多種性格檔案
        $positiveProfiles = [
            [
                'character_archetype' => '陽光探險家',
                'personality_type' => 'ENFP - 熱情洋溢的活動家',
                'traits' => ['樂觀開朗', '充滿好奇心', '善於社交', '富有創意'],
                'style_keywords' => ['活力四射', '色彩繽紛', '休閒舒適', '年輕時尚'],
                'color_palette' => ['#FFD700', '#FF6B6B', '#4ECDC4', '#95E1D3']
            ],
            [
                'character_archetype' => '熱情冒險者',
                'personality_type' => 'ESFP - 活力四射的表演者',
                'traits' => ['外向活潑', '熱愛生活', '即興發揮', '享受當下'],
                'style_keywords' => ['大膽前衛', '撞色混搭', '街頭潮流', '個性張揚'],
                'color_palette' => ['#FF1744', '#FFD600', '#00E676', '#00B0FF']
            ],
            [
                'character_archetype' => '創意夢想家',
                'personality_type' => 'ENTP - 機智靈活的辯論家',
                'traits' => ['聰明機智', '創新思維', '挑戰傳統', '充滿想像'],
                'style_keywords' => ['創意混搭', '前衛設計', '實驗風格', '不拘一格'],
                'color_palette' => ['#9C27B0', '#00BCD4', '#FFEB3B', '#FF5722']
            ],
            [
                'character_archetype' => '活力領導者',
                'personality_type' => 'ENTJ - 大膽果斷的指揮官',
                'traits' => ['自信果斷', '目標導向', '領導魅力', '效率至上'],
                'style_keywords' => ['都會俐落', '專業氣場', '現代簡約', '質感優先'],
                'color_palette' => ['#212121', '#1976D2', '#C62828', '#F5F5F5']
            ],
        ];

        // 中性情緒 - 多種性格檔案
        $neutralProfiles = [
            [
                'character_archetype' => '理性建築師',
                'personality_type' => 'INTJ - 深思熟慮的策略家',
                'traits' => ['冷靜理性', '邏輯清晰', '追求效率', '獨立思考'],
                'style_keywords' => ['簡約俐落', '質感優先', '極簡主義', '都會風格'],
                'color_palette' => ['#2C3E50', '#7F8C8D', '#BDC3C7', '#ECF0F1']
            ],
            [
                'character_archetype' => '沉穩觀察者',
                'personality_type' => 'ISTJ - 務實可靠的檢查員',
                'traits' => ['踏實穩重', '注重細節', '責任感強', '傳統經典'],
                'style_keywords' => ['經典耐看', '低調優雅', '傳統剪裁', '品質至上'],
                'color_palette' => ['#37474F', '#546E7A', '#78909C', '#B0BEC5']
            ],
            [
                'character_archetype' => '知性分析師',
                'personality_type' => 'INTP - 創新理性的思想家',
                'traits' => ['好奇探索', '邏輯分析', '獨立自主', '追求真理'],
                'style_keywords' => ['知性文藝', '簡約舒適', '實用主義', '低調內斂'],
                'color_palette' => ['#455A64', '#607D8B', '#90A4AE', '#CFD8DC']
            ],
            [
                'character_archetype' => '平衡協調者',
                'personality_type' => 'ISFJ - 細心體貼的守護者',
                'traits' => ['溫和友善', '細心體貼', '穩定可靠', '重視和諧'],
                'style_keywords' => ['溫柔優雅', '舒適自然', '柔和色調', '親切宜人'],
                'color_palette' => ['#8D6E63', '#A1887F', '#BCAAA4', '#D7CCC8']
            ],
        ];

        // 內斂情緒 - 多種性格檔案
        $introvertProfiles = [
            [
                'character_archetype' => '內斂詩人',
                'personality_type' => 'INFP - 理想主義的調停者',
                'traits' => ['情感細膩', '善於觀察', '內斂深沉', '追求意義'],
                'style_keywords' => ['低調優雅', '內斂沉穩', '文藝氣息', '復古質感'],
                'color_palette' => ['#34495E', '#5D4E6D', '#8B7E8B', '#A9B2AC']
            ],
            [
                'character_archetype' => '藝術靈魂',
                'personality_type' => 'ISFP - 靈活隨性的藝術家',
                'traits' => ['敏感細膩', '藝術氣質', '溫和寧靜', '追求美感'],
                'style_keywords' => ['藝術波西米亞', '自然舒適', '層次搭配', '獨特品味'],
                'color_palette' => ['#6D4C41', '#8D6E63', '#A1887F', '#D7CCC8']
            ],
            [
                'character_archetype' => '深度思考者',
                'personality_type' => 'INFJ - 富有洞察力的提倡者',
                'traits' => ['洞察深刻', '理想主義', '堅定信念', '富有同理心'],
                'style_keywords' => ['神秘優雅', '深沉內斂', '精緻細膩', '獨特品味'],
                'color_palette' => ['#263238', '#37474F', '#546E7A', '#78909C']
            ],
            [
                'character_archetype' => '寧靜守護者',
                'personality_type' => 'ISTP - 冷靜務實的工匠',
                'traits' => ['冷靜沉著', '實事求是', '獨立自主', '低調內斂'],
                'style_keywords' => ['機能實用', '簡約俐落', '工裝風格', '耐用質感'],
                'color_palette' => ['#424242', '#616161', '#757575', '#9E9E9E']
            ],
        ];

        // 根據情緒類型選擇對應的性格組
        $profiles = match ($emotionCategory) {
            'positive' => $positiveProfiles,
            'neutral' => $neutralProfiles,
            'introverted' => $introvertProfiles,
            default => $neutralProfiles
        };

        // 隨機選擇一組
        $profile = $profiles[array_rand($profiles)];

        return response()->json([
            'success' => true,
            'character_profile' => $profile,
            'emotion_category' => $emotionCategory
        ]);
    }

    // 新增輔助函數：將情緒分類
    private function getEmotionCategory($emotion)
    {
        $positiveEmotions = ['happy', 'excited', 'surprised', 'curious', 'inspired'];
        $neutralEmotions = ['neutral', 'focused', 'calm'];
        $introvertedEmotions = ['thoughtful', 'relaxed'];

        if (in_array($emotion, $positiveEmotions)) {
            return 'positive';
        } elseif (in_array($emotion, $neutralEmotions)) {
            return 'neutral';
        } elseif (in_array($emotion, $introvertedEmotions)) {
            return 'introverted';
        }

        return 'neutral';
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

        // 漫畫風格 Prompt（關鍵修改）
        $prompt = "Anime manga style illustration of {$genderDesc} embodying the '{$archetype}' personality archetype";
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