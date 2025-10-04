<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DemoController extends Controller
{
    public function processDemo(Request $request): JsonResponse
    {
        $step = $request->input('step');

        switch ($step) {
            case 'entry_qr':          return $this->generateEntryQR();
            case 'photo_upload':      return $this->processPhotoUpload();
            case 'video_emotion':     return $this->detectVideoEmotion();
            case 'accessory_matching':return $this->matchAccessories($request);
            case 'character_analysis':return $this->analyzeCharacter($request);
            case 'output_profile':    return $this->outputCharacterProfile($request);
            default:
                return response()->json(['success'=>false,'error'=>'Unknown step'], 400);
        }
    }

    private function generateEntryQR(): JsonResponse
    {
        // Demo 入口網址（之後你要接 Unity 或前端頁就改這裡）
        $entryUrl  = url('/character-creation');
        $size      = 220;
        $payload   = urlencode($entryUrl);
        $cacheBust = 'cb=' . time();

        // 改用穩定服務 + cache-busting
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$payload}&{$cacheBust}";

        return response()->json([
            'success'   => true,
            'qr_url'    => $qrUrl,
            'entry_url' => $entryUrl,
            'message'   => '掃描QR碼開始角色創建之旅'
        ]);
    }

    private function processPhotoUpload(): JsonResponse
    {
        // Demo 階段：模擬上傳成功（如果你已對接 /api/upload-photo，前端可走真上傳）
        return response()->json([
            'success'   => true,
            'message'   => '照片上傳成功，準備開始情緒偵測',
            'photo_id'  => time(),
            'next_step' => 'video_emotion'
        ]);
    }

    private function detectVideoEmotion(): JsonResponse
    {
        $emotionSequence = [
            ['time'=>'00:10','emotion'=>'neutral','confidence'=>0.8],
            ['time'=>'00:25','emotion'=>'surprised','confidence'=>0.9],
            ['time'=>'00:40','emotion'=>'happy','confidence'=>0.85],
            ['time'=>'01:00','emotion'=>'happy','confidence'=>0.92],
        ];
        $dominantEmotion = 'happy';

        return response()->json([
            'success'          => true,
            'emotion_sequence' => $emotionSequence,
            'dominant_emotion' => $dominantEmotion,
            'analysis_summary' => '觀看過程中情緒逐漸轉為積極正面',
            'next_step'        => 'accessory_matching'
        ]);
    }

    private function matchAccessories(Request $request): JsonResponse
    {
        $emotion = $request->input('emotion', 'happy');

        $db = [
            'happy' => ['hat'=>'陽光帽','glasses'=>'彩色太陽眼鏡','jewelry'=>'金色手鍊','scarf'=>'輕快絲巾','bag'=>'亮色小包'],
            'sad'   => ['hat'=>'針織毛帽','glasses'=>'復古眼鏡','jewelry'=>'銀色項鍊','scarf'=>'深色圍巾','bag'=>'簡約托特包'],
            'surprised'=>['hat'=>'貝雷帽','glasses'=>'圓框眼鏡','jewelry'=>'特色耳環','scarf'=>'印花絲巾','bag'=>'個性背包'],
        ];

        return response()->json([
            'success'         => true,
            'emotion'         => $emotion,
            'accessories'     => $db[$emotion] ?? $db['happy'],
            'styling_concept' => $this->getStyleConcept($emotion),
            'next_step'       => 'character_analysis'
        ]);
    }

    private function analyzeCharacter(Request $request): JsonResponse
    {
        $emotion     = $request->input('emotion', 'happy');
        $accessories = $request->input('accessories', []);

        $profiles = [
            'happy' => [
                'personality_type'=>'陽光活力型',
                'traits'=>['樂觀','熱情','親和力強','正能量'],
                'style_keywords'=>['明亮','活潑','時尚','自信'],
                'color_palette'=>['#FFD700','#FF6B6B','#4ECDC4','#45B7D1'],
                'character_archetype'=>'陽光使者'
            ],
            'sad' => [
                'personality_type'=>'深沉思考型',
                'traits'=>['敏感','內省','藝術氣質','共情能力強'],
                'style_keywords'=>['優雅','內斂','質感','詩意'],
                'color_palette'=>['#6C5CE7','#74B9FF','#636E72','#2D3436'],
                'character_archetype'=>'詩意靈魂'
            ],
            'surprised' => [
                'personality_type'=>'好奇探索型',
                'traits'=>['好奇心強','適應力佳','創新思維','靈活變通'],
                'style_keywords'=>['獨特','前衛','混搭','實驗性'],
                'color_palette'=>['#E17055','#FDCB6E','#6C5CE7','#00B894'],
                'character_archetype'=>'冒險探索者'
            ]
        ];

        $profile = $profiles[$emotion] ?? $profiles['happy'];

        return response()->json([
            'success'            => true,
            'character_profile'  => $profile,
            'overall_assessment' => $this->generateAssessment($emotion, $accessories),
            'next_step'          => 'output_profile'
        ]);
    }

    private function outputCharacterProfile(Request $request): JsonResponse
    {
        $characterData = $request->input('character_data');

        $profile = [
            'profile_id'            => 'CHAR_' . strtoupper(substr(md5((string) time()), 0, 8)),
            'creation_date'         => date('Y-m-d H:i:s'),
            'character_summary'     => $characterData,
            'style_recommendations' => $this->getStyleRecommendations(),
            'personality_insights'  => $this->getPersonalityInsights(),
            'compatibility_analysis'=> $this->getCompatibilityAnalysis()
        ];

        return response()->json([
            'success'           => true,
            'character_profile' => $profile,
            'download_ready'    => true,
            'share_options'     => ['social_media'=>true,'email'=>true,'qr_code'=>true]
        ]);
    }

    private function getStyleConcept(string $emotion): string
    {
        $concepts = [
            'happy'     => '明亮陽光風：展現內心的正能量與活力',
            'sad'       => '優雅知性風：體現深度思考與藝術氣息',
            'surprised' => '創意混搭風：呈現好奇心與探索精神'
        ];
        return $concepts[$emotion] ?? $concepts['happy'];
    }

    private function generateAssessment(string $emotion, array $accessories): string
    {
        return "基於你的情緒反應和風格偏好，系統分析出你是一個 {$emotion} 型性格的人，搭配的配飾展現了你獨特的個人魅力。";
    }

    private function getStyleRecommendations(): array
    {
        return ['日常穿搭建議','色彩搭配指南','配飾選擇要點','場合適配方案'];
    }

    private function getPersonalityInsights(): array
    {
        return ['核心性格特質分析','情緒表達模式','社交互動偏好','個人成長建議'];
    }

    private function getCompatibilityAnalysis(): array
    {
        return ['最佳搭檔類型','團隊合作模式','溝通風格分析'];
    }
}
