<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>個人風格角色檔案</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Noto Sans TC', '微軟正黑體', sans-serif;
            padding: 40px;
            background: #fff;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #7c3aed;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 32px;
            color: #7c3aed;
            margin-bottom: 10px;
        }
        .meta {
            color: #666;
            font-size: 14px;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 20px;
            color: #7c3aed;
            margin-bottom: 15px;
            border-left: 4px solid #06b6d4;
            padding-left: 12px;
        }
        .photos {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }
        .photo-box {
            text-align: center;
            width: 45%;
        }
        .photo-box img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
        }
        .photo-box p {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #06b6d4;
        }
        .info-box h4 {
            color: #7c3aed;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .info-box p {
            color: #555;
            font-size: 14px;
            line-height: 1.6;
        }
        .traits {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 15px 0;
        }
        .trait {
            background: #e8e4f3;
            color: #5b21b6;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .colors {
            display: flex;
            gap: 15px;
            margin: 15px 0;
        }
        .color {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #ddd;
        }
        ul {
            margin: 15px 0;
            padding-left: 25px;
        }
        li {
            margin: 8px 0;
            line-height: 1.6;
            color: #555;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #999;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>個人風格角色檔案</h1>
        <div class="meta">
            <p>檔案編號：{{ $profile_id }}</p>
            <p>生成日期：{{ $creation_date }}</p>
            <p>性別：{{ $gender === 'male' ? '男性' : '女性' }}</p>
        </div>
    </div>

    @if($uploaded_photo || $style_image)
    <div class="section">
        <h2 class="section-title">風格形象圖</h2>
        <div class="photos">
            @if($uploaded_photo)
            <div class="photo-box">
                <img src="{{ $uploaded_photo }}" alt="原始照片">
                <p>原始照片</p>
            </div>
            @endif
            @if($style_image)
            <div class="photo-box">
                <img src="{{ $style_image }}" alt="風格形象">
                <p>AI 生成風格形象</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if(!empty($character_data))
    <div class="section">
        <h2 class="section-title">角色性格分析</h2>
        <div class="info-grid">
            <div class="info-box">
                <h4>角色原型</h4>
                <p>{{ $character_data['character_archetype'] ?? 'N/A' }}</p>
            </div>
            <div class="info-box">
                <h4>性格類型</h4>
                <p>{{ $character_data['personality_type'] ?? 'N/A' }}</p>
            </div>
        </div>

        @if(!empty($character_data['traits']))
        <h4 style="margin: 20px 0 10px; color: #7c3aed;">性格特質</h4>
        <div class="traits">
            @foreach($character_data['traits'] as $trait)
                <span class="trait">{{ $trait }}</span>
            @endforeach
        </div>
        @endif

        @if(!empty($character_data['style_keywords']))
        <h4 style="margin: 20px 0 10px; color: #7c3aed;">風格關鍵字</h4>
        <p style="color: #555; font-size: 14px;">{{ implode(' • ', $character_data['style_keywords']) }}</p>
        @endif

        @if(!empty($character_data['color_palette']))
        <h4 style="margin: 20px 0 10px; color: #7c3aed;">專屬色彩</h4>
        <div class="colors">
            @foreach($character_data['color_palette'] as $color)
                <div class="color" style="background: {{ $color }}"></div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    @if(!empty($emotion_data))
    <div class="section">
        <h2 class="section-title">情緒分析</h2>
        <div class="info-box">
            <h4>主導情緒</h4>
            <p>{{ $emotion_data['dominant_emotion'] ?? 'N/A' }}</p>
        </div>
        @if(!empty($emotion_data['analysis_summary']))
        <p style="margin-top: 15px; color: #555; font-style: italic;">{{ $emotion_data['analysis_summary'] }}</p>
        @endif
    </div>
    @endif

    @if(!empty($accessory_data))
    <div class="section">
        <h2 class="section-title">配飾建議</h2>
        @if(!empty($accessory_data['styling_concept']))
        <p style="margin-bottom: 15px; color: #7c3aed; font-weight: 600;">{{ $accessory_data['styling_concept'] }}</p>
        @endif
        <ul>
            @foreach($accessory_data['accessories'] ?? [] as $key => $value)
                <li><strong>{{ $key }}:</strong> {{ $value }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="footer">
        <p>© {{ date('Y') }} AI 個人風格分析系統 | 此檔案由系統自動生成</p>
    </div>
</body>
</html>
