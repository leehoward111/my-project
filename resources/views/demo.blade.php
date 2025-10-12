@extends('layouts.app')

@section('title', 'AI 個人風格分析 - 體驗流程')

@section('head')
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        html,
        body {
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Noto Sans", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #0f172a;
            touch-action: manipulation;
        }

        a {
            color: inherit;
            text-decoration: none
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px
        }

        /* 按鈕樣式 */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border: 0;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(102, 126, 234, 0.4);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn.secondary {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #667eea;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn.success {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none
        }

        /* 導航欄 */
        nav {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 30px
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            letter-spacing: .4px;
            color: #667eea;
        }

        .brand-badge {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
        }

        .links {
            display: flex;
            gap: 12px
        }

        /* 主內容 */
        .main {
            padding: 50px 0 80px
        }

        /* 步驟卡片 */
        .step-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .step-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(180deg, #667eea, #764ba2);
            border-radius: 20px 0 0 20px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .step-card.active {
            border-color: rgba(102, 126, 234, 0.5);
            box-shadow: 0 15px 50px rgba(102, 126, 234, 0.2);
        }

        .step-card.active::before {
            opacity: 1;
        }

        .step-card.completed {
            border-color: rgba(16, 185, 129, 0.4);
        }

        .step-card.completed::before {
            background: linear-gradient(180deg, #10b981, #059669);
            opacity: 1;
        }

        .step-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px
        }

        .step-num {
            min-width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 20px;
            color: #fff;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .step-card.completed .step-num {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }

        .step-title {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            color: #0f172a
        }

        .step-content {
            margin-left: 64px
        }

        .step-desc {
            color: #64748b;
            margin: 0 0 20px;
            font-size: 15px;
            line-height: 1.6;
        }

        /* 上傳區域 */
        .upload-area {
            border: 3px dashed rgba(102, 126, 234, 0.4);
            border-radius: 16px;
            padding: 50px;
            text-align: center;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
            margin: 20px 0;
            cursor: pointer;
            transition: all 0.3s;
        }

        .upload-area:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
        }

        .upload-area p {
            margin: 10px 0;
            color: #64748b
        }

        .preview-img {
            max-width: 240px;
            max-height: 240px;
            border-radius: 16px;
            margin: 16px auto;
            display: block;
            border: 3px solid rgba(102, 126, 234, 0.3);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .generated-img {
            max-width: 100%;
            border-radius: 16px;
            margin: 20px 0;
            display: block;
            border: 3px solid rgba(102, 126, 234, 0.3);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        /* 影片容器 */
        .video-box {
            background: linear-gradient(135deg, #1e293b, #334155);
            border-radius: 16px;
            padding: 50px;
            text-align: center;
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .video-box h3 {
            color: #fff;
            margin: 0 0 16px;
            font-size: 20px;
        }

        .video-box p {
            color: #cbd5e1;
            margin: 10px 0;
            font-size: 15px;
        }

        /* 時間軸 */
        .timeline {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.08));
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 16px;
            padding: 24px;
            margin: 20px 0
        }

        .timeline h4 {
            margin: 0 0 16px;
            font-size: 18px;
            color: #0f172a;
            font-weight: 700;
        }

        .emotion-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.9);
            border-left: 4px solid #667eea;
            border-radius: 10px;
            margin: 10px 0;
            font-size: 15px;
            transition: all 0.3s;
        }

        .emotion-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        .emotion-item strong {
            min-width: 60px;
            color: #667eea;
            font-weight: 700;
        }

        /* 結果框 */
        .result-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.08));
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 16px;
            padding: 24px;
            margin: 20px 0
        }

        .result-box h4 {
            margin: 0 0 12px;
            font-size: 18px;
            color: #0f172a;
            font-weight: 700;
        }

        .result-box p {
            margin: 8px 0;
            font-size: 15px;
            color: #475569;
            line-height: 1.6;
        }

        /* 網格 */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin: 20px 0
        }

        /* 配飾卡片 */
        .acc-card {
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(226, 232, 240, 0.8);
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .acc-card.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.25);
            transform: translateY(-3px);
        }

        .acc-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .acc-card h4 {
            margin: 0 0 10px;
            font-size: 16px;
            color: #0f172a;
            font-weight: 700;
        }

        .acc-card p {
            margin: 6px 0;
            font-size: 14px;
            color: #64748b
        }

        /* 個人檔案框 */
        .profile-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.12), rgba(118, 75, 162, 0.12));
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 18px;
            padding: 28px;
            margin: 20px 0;
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.15);
        }

        .profile-box h3 {
            margin: 0 0 16px;
            font-size: 24px;
            color: #0f172a;
            font-weight: 800;
        }

        .profile-box h4 {
            margin: 20px 0 12px;
            font-size: 17px;
            color: #0f172a;
            font-weight: 700;
        }

        /* 特質標籤 */
        .traits {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 16px 0
        }

        .trait {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
            padding: 10px 18px;
            border-radius: 25px;
            font-size: 14px;
            color: #5b21b6;
            font-weight: 600;
            border: 1px solid rgba(102, 126, 234, 0.3);
            transition: all 0.3s;
        }

        .trait:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        /* 色彩圓圈 */
        .colors {
            display: flex;
            gap: 12px;
            margin: 16px 0
        }

        .color {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
            cursor: pointer;
        }

        .color:hover {
            transform: scale(1.15);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
        }

        /* 洞察清單 */
        .insight-list {
            margin: 16px 0;
            padding-left: 24px
        }

        .insight-list li {
            margin: 10px 0;
            color: #475569;
            font-size: 15px;
            line-height: 1.7
        }

        /* 載入動畫 */
        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, .3);
            border-top: 2px solid #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 8px
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        /* 圖片生成中 */
        .image-generating {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.08));
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            margin: 20px 0
        }

        .image-generating h4 {
            color: #0f172a;
            margin-bottom: 12px;
            font-size: 18px;
            font-weight: 700;
        }

        .image-generating p {
            color: #64748b;
            margin: 10px 0;
            font-size: 15px;
        }

        /* 影片樣式 */
        #emotionVideo {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
            max-height: 450px;
            border-radius: 12px;
        }

        #emotionVideo::-webkit-media-controls-panel {
            background: rgba(0, 0, 0, 0.8);
        }

        /* 響應式 */
        @media (max-width: 768px) {
            .nav-inner {
                padding: 0 20px;
                height: 60px;
            }

            .brand {
                font-size: 16px;
            }

            .brand-badge {
                width: 28px;
                height: 28px;
                font-size: 16px;
            }

            .step-card {
                padding: 24px 20px;
            }

            .step-header {
                gap: 12px;
            }

            .step-num {
                min-width: 42px;
                height: 42px;
                font-size: 18px;
            }

            .step-title {
                font-size: 18px;
            }

            .step-content {
                margin-left: 0;
                margin-top: 20px;
            }

            .upload-area {
                padding: 40px 20px;
            }

            .video-box {
                padding: 40px 20px;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }

        /* 手機橫屏優化 */
        @media (max-width: 768px) and (orientation: landscape) {
            .main {
                padding: 30px 0 50px;
            }

            .step-card {
                padding: 20px 16px;
            }

            .upload-area {
                padding: 30px 15px;
            }

            .video-box {
                padding: 30px 15px;
                min-height: 240px;
            }
        }

        /* 超小手機優化 */
        @media (max-width: 374px) {
            .container {
                padding: 0 12px;
            }

            .nav-inner {
                padding: 0 15px;
            }

            .brand {
                font-size: 14px;
            }

            .brand-badge {
                width: 26px;
                height: 26px;
                font-size: 14px;
            }

            .step-card {
                padding: 20px 15px;
            }

            .step-num {
                min-width: 38px;
                height: 38px;
                font-size: 16px;
            }

            .step-title {
                font-size: 16px;
            }

            .btn {
                padding: 12px 20px;
                font-size: 14px;
            }

            .upload-area {
                padding: 30px 15px;
            }

            .preview-img {
                max-width: 180px;
                max-height: 180px;
            }
        }

        /* 平板優化 */
        @media (min-width: 769px) and (max-width: 1024px) {
            .container {
                max-width: 800px;
            }

            .step-card {
                padding: 28px 24px;
            }
        }

        /* 大螢幕優化 */
        @media (min-width: 1400px) {
            .container {
                max-width: 1000px;
            }

            .nav-inner {
                max-width: 1200px;
            }
        }

        /* 觸控裝置優化 */
        @media (hover: none) and (pointer: coarse) {
            .btn:hover {
                transform: none;
            }

            .btn:active {
                transform: scale(0.95);
            }

            .acc-card:hover {
                transform: none;
            }

            .acc-card:active {
                transform: scale(0.98);
            }

            .upload-area:hover {
                transform: none;
                border-color: rgba(102, 126, 234, 0.4);
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
            }

            .upload-area:active {
                transform: scale(0.98);
                border-color: #667eea;
            }
        }

        /* 確保影片在所有裝置正常顯示 */
        #emotionVideo {
            max-width: 100%;
            height: auto;
        }

        /* 網格在小螢幕自適應 */
        @media (max-width: 480px) {
            .grid {
                grid-template-columns: 1fr !important;
                gap: 12px;
            }
        }
    </style>
@endsection

@section('content')
    <nav>
        <div class="nav-inner">
            <div class="brand">
                <div class="brand-badge">✨</div>
                <div>AI 個人風格分析</div>
            </div>
            <div class="links">
                <a class="btn secondary" href="{{ route('landing') }}">← 返回首頁</a>
            </div>
        </div>
    </nav>

    <main class="main">
        <div class="container">

            <!-- Step 1: 照片上傳 -->
            <div class="step-card active" id="step1">
                <div class="step-header">
                    <div class="step-num">1</div>
                    <div class="step-title">上傳照片</div>
                </div>
                <div class="step-content">
                    <p class="step-desc">選擇性別並上傳個人照片建立風格基礎</p>

                    <div style="margin-bottom: 24px;">
                        <h4 style="margin-bottom: 14px; font-size: 17px; color: #0f172a; font-weight: 700;">選擇性別</h4>
                        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 14px;">
                            <div class="acc-card" id="maleBtn" onclick="selectGender('male')">
                                <h4>👨 男性</h4>
                                <p>Male</p>
                            </div>
                            <div class="acc-card" id="femaleBtn" onclick="selectGender('female')">
                                <h4>👩 女性</h4>
                                <p>Female</p>
                            </div>
                        </div>
                    </div>

                    <div class="upload-area" onclick="document.getElementById('photoInput').click()">
                        <div id="photoPreview">
                            <p style="font-size:18px;margin-bottom:10px;font-weight:600;color:#667eea">📸 點擊上傳照片</p>
                            <p>支援 JPG, PNG 格式</p>
                        </div>
                    </div>
                    <input type="file" id="photoInput" accept="image/*" style="display:none"
                        onchange="handlePhotoUpload()" />
                    <button class="btn" id="uploadBtn" onclick="uploadPhoto()" disabled>✓ 確認上傳</button>
                    <div id="uploadResult" class="result-box" style="display:none"></div>
                </div>
            </div>

            <!-- Step 2: 影片情緒 -->
            <div class="step-card" id="step2">
                <div class="step-header">
                    <div class="step-num">2</div>
                    <div class="step-title">影片情緒偵測</div>
                </div>
                <div class="step-content">
                    <p class="step-desc">選擇影片並觀看，系統將偵測您的情緒變化</p>

                    <!-- 影片選擇 -->
                    <div id="videoSelection" style="margin-bottom: 24px;">
                        <h4 style="margin-bottom: 14px; font-size: 17px; color: #0f172a; font-weight: 700;">選擇影片</h4>
                        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 14px;">
                            <div class="acc-card" id="video1Btn" onclick="selectVideo(1)">
                                <h4>🎬 Emotional Mirror (1)</h4>
                                <p>影片選項 1</p>
                            </div>
                            <div class="acc-card" id="video2Btn" onclick="selectVideo(2)">
                                <h4>🎬 Emotional Mirror (2)</h4>
                                <p>影片選項 2</p>
                            </div>
                        </div>
                    </div>

                    <div class="video-box" id="videoContainer">
                        <video id="emotionVideo" width="100%" controls controlsList="nodownload"
                            style="display:none; border-radius:12px;">
                            <source id="videoSource" src="" type="video/mp4">
                            您的瀏覽器不支援影片播放。
                        </video>

                        <div id="videoPlaceholder">
                            <h3 style="color:#fff; margin-bottom:14px;">🎥 請先選擇影片</h3>
                            <p style="color:#cbd5e1;">請從上方選擇一部影片開始體驗</p>
                        </div>
                    </div>

                    <div id="emotionTimeline" class="timeline" style="display:none">
                        <h4>📊 情緒時間軸</h4>
                        <div id="emotionPoints"></div>
                    </div>
                    <div id="emotionSummary" class="result-box" style="display:none"></div>
                </div>
            </div>

            <!-- Step 3: 配飾搭配 + 性格分析 -->
            <div class="step-card" id="step3">
                <div class="step-header">
                    <div class="step-num">3</div>
                    <div class="step-title">智能配飾搭配 & 角色性格分析</div>
                </div>
                <div class="step-content">
                    <p class="step-desc">基於情緒分析生成配飾建議與性格原型</p>

                    <h4 style="margin: 24px 0 14px; font-size: 19px; color: #0f172a; font-weight: 700;">👗 配飾搭配</h4>
                    <div id="accessoriesDisplay" class="grid"></div>

                    <h4 style="margin: 32px 0 14px; font-size: 19px; color: #0f172a; font-weight: 700;">🎭 性格分析</h4>
                    <div id="characterAnalysis"></div>

                    <button class="btn" id="analysisBtn" onclick="generateAnalysis()" disabled>🔍 開始分析</button>
                </div>
            </div>

            <!-- Step 4: 生成風格形象 + 下載檔案 -->
            <div class="step-card" id="step4">
                <div class="step-header">
                    <div class="step-num">4</div>
                    <div class="step-title">生成風格形象 & 下載檔案</div>
                </div>
                <div class="step-content">
                    <p class="step-desc">使用 AI 生成專屬風格形象圖並下載完整檔案</p>

                    <h4 style="margin: 24px 0 14px; font-size: 19px; color: #0f172a; font-weight: 700;">🎨 風格形象</h4>
                    <div id="imageGenArea"></div>
                    <button class="btn" id="imageBtn" onclick="generateStyleImage()" disabled>✨ 生成形象圖</button>

                    <div id="finalProfile" style="margin-top: 32px;"></div>
                    <button class="btn success" id="downloadBtn" onclick="downloadProfile()" disabled
                        style="margin-top:20px">📥 下載完整檔案</button>
                </div>
            </div>

        </div>
    </main>
@endsection

@section('scripts')
    <script>
        const API_DEMO = "{{ url('/api/demo') }}";
        const API_UPLOAD = "{{ url('/api/upload-photo') }}";
        let currentStep = 1;
        let userData = {
            gender: null,
            photoFile: null,
            uploadedPhotoUrl: null,
            uploadedPhotoBase64: null,
            photoUploaded: false,
            emotionData: null,
            accessoryData: null,
            characterData: null,
            styleImageUrl: null
        };

        let selectedVideo = null;

        function selectVideo(videoNum) {
            selectedVideo = videoNum;

            document.getElementById('video1Btn').classList.remove('selected');
            document.getElementById('video2Btn').classList.remove('selected');
            document.getElementById('video' + videoNum + 'Btn').classList.add('selected');

            const videoSource = document.getElementById('videoSource');
            const video = document.getElementById('emotionVideo');
            const placeholder = document.getElementById('videoPlaceholder');

            if (videoNum === 1) {
                videoSource.src = '{{ asset("videos/emotional-mirror.mp4") }}';
            } else {
                videoSource.src = '{{ asset("videos/emotional-mirror-2.mp4") }}';
            }

            video.load();

            placeholder.innerHTML = '<h3 style="color:#fff; margin-bottom:14px;">🎬 Emotional Mirror (' + videoNum + ')</h3><p style="color:#cbd5e1; margin-bottom:24px;">點擊開始播放並偵測情緒</p><button class="btn" onclick="startVideoWithEmotion()">▶️ 開始播放</button>';
        }

        function selectGender(gender) {
            userData.gender = gender;
            document.getElementById('maleBtn').classList.remove('selected');
            document.getElementById('femaleBtn').classList.remove('selected');
            if (gender === 'male') {
                document.getElementById('maleBtn').classList.add('selected');
            } else {
                document.getElementById('femaleBtn').classList.add('selected');
            }
            checkUploadReady();
        }

        function checkUploadReady() {
            const btn = document.getElementById('uploadBtn');
            if (userData.gender && userData.photoFile) {
                btn.disabled = false;
            } else {
                btn.disabled = true;
            }
        }

        function handlePhotoUpload() {
            const file = document.getElementById('photoInput').files[0];
            if (file) {
                userData.photoFile = file;
                const r = new FileReader();
                r.onload = e => {
                    document.getElementById('photoPreview').innerHTML = '<img src="' + e.target.result + '" class="preview-img" alt="預覽"><p style="margin-top:12px;color:#667eea;font-weight:600">✓ 照片已選擇</p>';
                };
                r.readAsDataURL(file);
                checkUploadReady();
            }
        }

        async function uploadPhoto() {
            if (!userData.gender) {
                alert('請先選擇性別');
                return;
            }
            if (!userData.photoFile) {
                alert('請先上傳照片');
                return;
            }

            const btn = document.getElementById('uploadBtn');
            btn.innerHTML = '⏳ 上傳中<span class="loading"></span>';
            btn.disabled = true;

            try {
                const formData = new FormData();
                formData.append('photo', userData.photoFile);
                formData.append('gender', userData.gender);

                const uploadRes = await fetch(API_UPLOAD, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const uploadData = await uploadRes.json();

                if (!uploadData.success) {
                    throw new Error(uploadData.error || '上傳失敗');
                }

                userData.uploadedPhotoUrl = uploadData.photo_url;
                userData.uploadedPhotoBase64 = uploadData.photo_base64;
                userData.photoUploaded = true;

                const resultDiv = document.getElementById('uploadResult');
                resultDiv.innerHTML = '<h4>✅ 上傳成功</h4><p><strong>性別：</strong>' + (userData.gender === 'male' ? '男性 👨' : '女性 👩') + '</p><img src="' + uploadData.photo_url + '" class="preview-img" alt="已上傳" style="margin-top:16px">';
                resultDiv.style.display = 'block';
                btn.innerHTML = '✓ 上傳完成';

                setTimeout(nextStep, 800);
            } catch (e) {
                alert('上傳失敗：' + e.message);
                btn.innerHTML = '🔄 重新上傳';
                btn.disabled = false;
            }
        }

        async function startVideoWithEmotion() {
            if (!selectedVideo) {
                alert('請先選擇影片');
                return;
            }

            const placeholder = document.getElementById('videoPlaceholder');
            const video = document.getElementById('emotionVideo');

            placeholder.style.display = 'none';
            video.style.display = 'block';
            video.play();

            video.addEventListener('ended', async () => {
                const videoContainer = document.getElementById('videoContainer');
                videoContainer.innerHTML = '<div style="padding:50px; text-align:center;"><h3 style="color:#fff;margin-bottom:16px;">🧠 分析情緒中...</h3><p style="color:#cbd5e1;">AI 正在處理您的情緒數據</p><div class="loading" style="margin:24px auto;border-color:rgba(255,255,255,0.3);border-top-color:#fff;width:24px;height:24px;"></div></div>';

                try {
                    const res = await fetch(API_DEMO, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ step: 'video_emotion' })
                    });

                    const data = await res.json();
                    if (!data.success) throw new Error(data.error || '偵測失敗');

                    userData.emotionData = data;

                    const timeline = document.getElementById('emotionTimeline');
                    const points = document.getElementById('emotionPoints');
                    points.innerHTML = data.emotion_sequence.map(it =>
                        '<div class="emotion-item"><strong>⏱️ ' + it.time + '</strong><span>' + getEmotionText(it.emotion) + ' (' + (it.confidence * 100).toFixed(0) + '%)</span></div>'
                    ).join('');
                    timeline.style.display = 'block';

                    const summary = document.getElementById('emotionSummary');
                    summary.innerHTML = '<h4>✅ 分析完成</h4><p><strong>🎯 主導情緒：</strong>' + getEmotionText(data.dominant_emotion) + '</p><p><strong>📝 總結：</strong>' + data.analysis_summary + '</p>';
                    summary.style.display = 'block';

                    videoContainer.innerHTML = '<div style="padding:50px; text-align:center;"><h3 style="color:#fff;margin-bottom:12px;">✅ 播放完畢</h3><p style="color:#cbd5e1;">影片 ' + selectedVideo + ' 情緒偵測已完成</p></div>';

                    setTimeout(nextStep, 1000);
                } catch (e) {
                    alert('偵測失敗：' + e.message);
                }
            }, { once: true });
        }

        async function generateAnalysis() {
            const btn = document.getElementById('analysisBtn');
            btn.innerHTML = '⏳ 分析中<span class="loading"></span>';
            btn.disabled = true;

            try {
                const accRes = await fetch(API_DEMO, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        step: 'accessory_matching',
                        emotion: userData.emotionData?.dominant_emotion || 'happy'
                    })
                });
                const accData = await accRes.json();
                if (!accData.success) throw new Error('配飾搭配失敗');
                userData.accessoryData = accData;

                const display = document.getElementById('accessoriesDisplay');

                const labelMap = {
                    'top': '👕 上衣',
                    'bottom': '👖 下身',
                    'accessory': '💍 配件'
                };

                display.innerHTML = Object.entries(accData.accessories).map(([k, v]) =>
                    '<div class="acc-card selected"><h4>' + labelMap[k] + '</h4><p style="font-weight:600;margin:10px 0">' + v + '</p></div>'
                ).join('');

                const charRes = await fetch(API_DEMO, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        step: 'character_analysis',
                        emotion: userData.emotionData?.dominant_emotion || 'happy',
                        accessories: userData.accessoryData?.accessories || {}
                    })
                });
                const charData = await charRes.json();
                if (!charData.success) throw new Error('性格分析失敗');
                userData.characterData = charData.character_profile;

                const analysis = document.getElementById('characterAnalysis');
                const prof = userData.characterData;
                analysis.innerHTML = '<div class="profile-box"><h3>🎭 ' + prof.character_archetype + '</h3><h4 style="margin:10px 0;color:#667eea;font-size:16px;">' + prof.personality_type + '</h4><div class="traits">' + prof.traits.map(t => '<div class="trait">' + t + '</div>').join('') + '</div><h4>🏷️ 風格關鍵字</h4><p style="margin:10px 0;color:#475569;font-size:15px;">' + prof.style_keywords.join(' • ') + '</p><h4>🎨 專屬色彩</h4><div class="colors">' + prof.color_palette.map(c => '<div class="color" style="background:' + c + '" title="' + c + '"></div>').join('') + '</div></div>';

                btn.innerHTML = '✓ 分析完成';
                setTimeout(nextStep, 800);
            } catch (e) {
                alert('分析失敗：' + e.message);
                btn.innerHTML = '🔄 重新分析';
                btn.disabled = false;
            }
        }

        async function generateStyleImage() {
            const btn = document.getElementById('imageBtn');
            const area = document.getElementById('imageGenArea');
            btn.innerHTML = '⏳ 生成中（約 30 秒）<span class="loading"></span>';
            btn.disabled = true;
            area.innerHTML = '<div class="image-generating"><h4>🎨 AI 正在生成您的專屬風格形象...</h4><p>這需要約 30 秒時間，請稍候</p><div class="loading" style="margin:24px auto;border-color:rgba(102,126,234,0.3);border-top-color:#667eea;width:24px;height:24px;"></div></div>';

            try {
                const res = await fetch(API_DEMO, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        step: 'generate_style_image',
                        image_url: userData.uploadedPhotoBase64,
                        gender: userData.gender,
                        character_data: userData.characterData,
                        emotion: userData.emotionData?.dominant_emotion || 'happy',
                        accessories: userData.accessoryData?.accessories || {}
                    })
                });
                const data = await res.json();
                if (!data.success) throw new Error(data.error || '生成失敗');
                userData.styleImageUrl = data.image_url;

                area.innerHTML = '<div class="result-box"><h4>✅ 風格形象生成成功！</h4><p style="margin: 16px 0;"><strong>📷 原始照片：</strong></p><img src="' + userData.uploadedPhotoUrl + '" alt="原始照片" class="generated-img" style="max-width: 320px;" /><p style="margin: 16px 0;"><strong>✨ 生成的風格形象：</strong></p><img src="' + data.image_url + '" alt="風格形象" class="generated-img" /><p style="font-size:14px;color:#64748b;margin-top:12px;">性別：' + (userData.gender === 'male' ? '男性 👨' : '女性 👩') + '</p></div>';
                btn.innerHTML = '✓ 生成完成';

                document.getElementById('downloadBtn').disabled = false;
                document.getElementById('finalProfile').innerHTML = '<div class="result-box"><h4>📦 檔案已準備完成</h4><p>點擊下方按鈕下載您的完整風格檔案</p></div>';
            } catch (e) {
                alert('圖片生成失敗：' + e.message);
                area.innerHTML = '<div class="result-box" style="border-color:#ef4444;background:rgba(239,68,68,0.1);"><h4 style="color:#ef4444">❌ 生成失敗</h4><p>' + e.message + '</p></div>';
                btn.innerHTML = '🔄 重新生成';
                btn.disabled = false;
            }
        }

        async function downloadProfile() {
            try {
                const btn = document.getElementById('downloadBtn');
                btn.innerHTML = '⏳ 生成檔案中<span class="loading"></span>';
                btn.disabled = true;

                const response = await fetch("{{ url('/api/generate-pdf') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        gender: userData.gender,
                        character_data: userData.characterData,
                        style_image_url: userData.styleImageUrl,
                        uploaded_photo_url: userData.uploadedPhotoUrl,
                        emotion_data: userData.emotionData,
                        accessory_data: userData.accessoryData
                    })
                });

                const html = await response.text();
                const newWindow = window.open('', '_blank');
                newWindow.document.write(html);
                newWindow.document.close();

                btn.innerHTML = '📥 下載檔案';
                btn.disabled = false;
                alert('✅ 檔案已在新視窗開啟，您可以使用瀏覽器的列印功能另存為 PDF（Ctrl+P）');
            } catch (e) {
                alert('檔案生成失敗：' + e.message);
                document.getElementById('downloadBtn').innerHTML = '📥 下載檔案';
                document.getElementById('downloadBtn').disabled = false;
            }
        }

        function nextStep() {
            if (currentStep < 4) {
                document.getElementById('step' + currentStep).classList.remove('active');
                document.getElementById('step' + currentStep).classList.add('completed');
                currentStep++;
                document.getElementById('step' + currentStep).classList.add('active');
                enableNextStepButton();
                document.getElementById('step' + currentStep).scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        function enableNextStepButton() {
            if (currentStep === 3) {
                document.getElementById('analysisBtn').disabled = false;
            } else if (currentStep === 4) {
                document.getElementById('imageBtn').disabled = false;
            }
        }

        function getEmotionText(e) {
            const m = {
                happy: '😊 開心',
                excited: '🤩 興奮',
                surprised: '😲 驚喜',
                curious: '🤔 好奇',
                neutral: '😐 平靜',
                focused: '🧐 專注',
                thoughtful: '💭 沉思',
                relaxed: '😌 放鬆',
                calm: '😇 從容',
                inspired: '💡 受啟發'
            };
            return m[e] || e;
        }

        // 防止雙擊縮放
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function (event) {
            const now = Date.now();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // 視窗大小改變時調整
        window.addEventListener('resize', function () {
            // 強制重新計算佈局
            document.body.style.height = window.innerHeight + 'px';
        });

    </script>
@endsection