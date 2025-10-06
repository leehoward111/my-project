<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>個人風格角色檔案</title>
    <style>
        @media print {
            @page { margin: 2cm; }
            body { margin: 0; }
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Microsoft JhengHei', '微軟正黑體', Arial, sans-serif;
            padding: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 60px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
            border-bottom: 4px solid #667eea;
            padding-bottom: 30px;
        }
        .header h1 {
            font-size: 42px;
            color: #667eea;
            margin-bottom: 15px;
            font-weight: 700;
        }
        .meta {
            color: #666;
            font-size: 16px;
            line-height: 1.8;
        }
        .section {
            margin: 40px 0;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 28px;
            color: #667eea;
            margin-bottom: 20px;
            border-left: 6px solid #764ba2;
            padding-left: 15px;
            font-weight: 600;
        }
        .photos {
            display: flex;
            justify-content: space-around;
            gap: 30px;
            margin: 30px 0;
        }
        .photo-box {
            flex: 1;
            text-align: center;
        }
        .photo-box img {
            max-width: 100%;
            border-radius: 15px;
            border: 4px solid #e0e0e0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .photo-box p {
            margin-top: 15px;
            font-size: 16px;
            color: #666;
            font-weight: 600;
        }
        .info-box {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 25px;
            border-radius: 15px;
            margin: 20px 0;
            border-left: 5px solid #667eea;
        }
        .info-box h4 {
            color: #667eea;
            margin-bottom: 12px;
            font-size: 20px;
        }
        .info-box p {
            color: #333;
            font-size: 16px;
            line-height: 1.8;
        }
        .traits {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 20px 0;
        }
        .trait {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .colors {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }
        .color {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid #ddd;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        ul {
            margin: 20px 0;
            padding-left: 30px;
        }
        li {
            margin: 12px 0;
            line-height: 1.8;
            color: #555;
            font-size: 16px;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            color: #999;
            font-size: 14px;
            border-top: 2px solid #ddd;
            padding-top: 30px;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .print-btn:hover {
            background: #764ba2;
        }
        @media print {
            .print-btn { display: none; }
            body { background: white; }
            .container { box-shadow: none; padding: 20px; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">列印 / 儲存 PDF</button>
    
    <div class="container">
        <div class="header">
            <h1>個人風格角色檔案</h1>
            <div class="meta">
                <p><strong>檔案編號：</strong>{{ $profile_id }}</p>
                <p><strong>生成日期：</strong>{{ $creation_date }}</p>
                <p><strong>性別：</strong>{{ $gender === 'male' ? '男性' : '女性' }}</p>
            </div>
        </div>

        @if($uploaded_photo_url || $style_image_url)
        <div class="section">
            <h2 class="section-title">風格形象圖</h2>
            <div class="photos">
                @if($uploaded_photo_url)
                <div class="photo-box">
                    <img src="{{ $uploaded_photo_url }}" alt="原始照片">
                    <p>原始照片</p>
                </div>
                @endif
                @if($style_image_url)
                <div class="photo-box">
                    <img src="{{ $style_image_url }}" alt="風格形象">
                    <p>AI 生成風格形象</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if(!empty($character_data))
        <div class="section">
            <h2 class="section-title">角色性格分析</h2>
            
            <div class="info-box">
                <h4>角色原型</h4>
                <p>{{ $character_data['character_archetype'] ?? 'N/A' }}</p>
            </div>
            
            <div class="info-box">
                <h4>性格類型</h4>
                <p>{{ $character_data['personality_type'] ?? 'N/A' }}</p>
            </div>

            @if(!empty($character_data['traits']))
            <h4 style="margin: 30px 0 15px; color: #667eea; font-size: 20px;">性格特質</h4>
            <div class="traits">
                @foreach($character_data['traits'] as $trait)
                    <span class="trait">{{ $trait }}</span>
                @endforeach
            </div>
            @endif

            @if(!empty($character_data['style_keywords']))
            <div class="info-box" style="margin-top: 20px;">
                <h4>風格關鍵字</h4>
                <p>{{ implode(' • ', $character_data['style_keywords']) }}</p>
            </div>
            @endif

            @if(!empty($character_data['color_palette']))
            <h4 style="margin: 30px 0 15px; color: #667eea; font-size: 20px;">專屬色彩</h4>
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
                @if(!empty($emotion_data['analysis_summary']))
                <p style="margin-top: 15px; font-style: italic;">{{ $emotion_data['analysis_summary'] }}</p>
                @endif
            </div>
        </div>
        @endif

        @if(!empty($accessory_data))
        <div class="section">
            <h2 class="section-title">配飾建議</h2>
            @if(!empty($accessory_data['styling_concept']))
            <div class="info-box">
                <h4>風格概念</h4>
                <p>{{ $accessory_data['styling_concept'] }}</p>
            </div>
            @endif
            <ul style="margin-top: 20px;">
                @foreach($accessory_data['accessories'] ?? [] as $key => $value)
                    <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="footer">
            <p>© {{ date('Y') }} AI 個人風格分析系統</p>
            <p>此檔案由系統自動生成 | 使用瀏覽器的列印功能可另存為 PDF</p>
        </div>
    </div>
</body>
</html>
