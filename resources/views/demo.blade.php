@extends('layouts.app')

@section('title', 'AI 個人風格分析 - 體驗流程')

@section('head')
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        html,
        body {
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden
        }

        body {
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Noto Sans", Arial;
            background: #4a9fd8;
            color: #0f172a
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

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #7c3aed, #06b6d4);
            color: #fff;
            border: 0;
            padding: 12px 20px;
            border-radius: 14px;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(124, 58, 237, 0.3);
            cursor: pointer;
            transition: .2s;
            font-size: 14px
        }

        .btn.secondary {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #e2e8f0;
            color: #475569;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05)
        }

        .btn.success {
            background: linear-gradient(135deg, #22c55e, #16a34a)
        }

        .btn:hover {
            transform: translateY(-1px)
        }

        .btn:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none
        }

        nav {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05)
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 20px
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            letter-spacing: .4px
        }

        .brand-badge {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: linear-gradient(135deg, #7c3aed, #06b6d4)
        }

        .links {
            display: flex;
            gap: 10px
        }

        .main {
            padding: 80px 0 60px
        }

        .step-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: .3s
        }

        .step-card.active {
            border-color: rgba(124, 58, 237, .4);
            background: rgba(255, 255, 255, 0.98)
        }

        .step-card.completed {
            border-left: 4px solid #22c55e
        }

        .step-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px
        }

        .step-num {
            min-width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #7c3aed, #06b6d4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            color: #fff
        }

        .step-card.completed .step-num {
            background: linear-gradient(135deg, #22c55e, #16a34a)
        }

        .step-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            color: #0f172a
        }

        .step-content {
            margin-left: 54px
        }

        .step-desc {
            color: #64748b;
            margin: 0 0 16px;
            font-size: 14px
        }

        .qr-box {
            background: linear-gradient(135deg, rgba(124, 58, 237, .08), rgba(6, 182, 212, .08));
            border: 1px solid rgba(124, 58, 237, .2);
            border-radius: 14px;
            padding: 28px;
            text-align: center;
            margin: 16px 0
        }

        .qr-box h3 {
            margin: 0 0 8px;
            font-size: 18px;
            color: #0f172a
        }

        .qr-box p {
            color: #475569;
            margin: 8px 0;
            font-size: 14px
        }

        .upload-area {
            border: 2px dashed rgba(124, 58, 237, .3);
            border-radius: 14px;
            padding: 40px;
            text-align: center;
            background: rgba(124, 58, 237, .03);
            margin: 16px 0;
            cursor: pointer;
            transition: .3s
        }

        .upload-area:hover {
            border-color: #7c3aed;
            background: rgba(124, 58, 237, .08)
        }

        .upload-area p {
            margin: 8px 0;
            color: #64748b
        }

        .preview-img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 12px;
            margin: 12px auto;
            display: block;
            border: 2px solid rgba(124, 58, 237, .2)
        }

        .generated-img {
            max-width: 100%;
            border-radius: 12px;
            margin: 16px 0;
            display: block;
            border: 2px solid rgba(124, 58, 237, .3)
        }

        .video-box {
            background: #1e293b;
            border-radius: 14px;
            padding: 40px;
            text-align: center;
            min-height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 16px 0
        }

        .video-box h3 {
            color: #fff;
            margin: 0 0 12px
        }

        .video-box p {
            color: #cbd5e1;
            margin: 8px 0
        }

        .timeline {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
            padding: 20px;
            margin: 16px 0
        }

        .timeline h4 {
            margin: 0 0 12px;
            font-size: 16px;
            color: #0f172a
        }

        .emotion-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.9);
            border-left: 3px solid #06b6d4;
            border-radius: 8px;
            margin: 8px 0;
            font-size: 14px
        }

        .emotion-item strong {
            min-width: 50px;
            color: #06b6d4
        }

        .result-box {
            background: rgba(124, 58, 237, .05);
            border: 1px solid rgba(124, 58, 237, .2);
            border-radius: 12px;
            padding: 16px;
            margin: 16px 0
        }

        .result-box h4 {
            margin: 0 0 8px;
            font-size: 16px;
            color: #0f172a
        }

        .result-box p {
            margin: 6px 0;
            font-size: 14px;
            color: #475569
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 14px;
            margin: 16px 0
        }

        .acc-card {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
            padding: 18px;
            text-align: center;
            transition: .3s
        }

        .acc-card.selected {
            border-color: #7c3aed;
            background: rgba(124, 58, 237, .08)
        }

        .acc-card h4 {
            margin: 0 0 8px;
            font-size: 15px;
            color: #0f172a
        }

        .acc-card p {
            margin: 4px 0;
            font-size: 13px;
            color: #64748b
        }

        .profile-box {
            background: linear-gradient(135deg, rgba(124, 58, 237, .08), rgba(6, 182, 212, .08));
            border: 1px solid rgba(124, 58, 237, .2);
            border-radius: 14px;
            padding: 24px;
            margin: 16px 0
        }

        .profile-box h3 {
            margin: 0 0 12px;
            font-size: 20px;
            color: #0f172a
        }

        .profile-box h4 {
            margin: 16px 0 8px;
            font-size: 16px;
            color: #0f172a
        }

        .traits {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 12px 0
        }

        .trait {
            background: rgba(124, 58, 237, .12);
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 13px;
            color: #5b21b6
        }

        .colors {
            display: flex;
            gap: 10px;
            margin: 12px 0
        }

        .color {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid rgba(0, 0, 0, .1)
        }

        .insight-list {
            margin: 12px 0;
            padding-left: 20px
        }

        .insight-list li {
            margin: 8px 0;
            color: #475569;
            font-size: 14px;
            line-height: 1.6
        }

        .loading {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, .3);
            border-top: 2px solid #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 6px
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        .image-generating {
            background: rgba(124, 58, 237, .05);
            border: 1px solid rgba(124, 58, 237, .2);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            margin: 16px 0
        }

        .image-generating p {
            color: #475569;
            margin: 8px 0
        }
    </style>
@endsection

@section('content')
    <nav>
        <div class="nav-inner">
            <div class="brand">
                <div class="brand-badge"></div>
                <div>AI 個人風格分析</div>
            </div>
            <div class="links">
                <a class="btn secondary" href="{{ route('landing') }}">首頁</a>
                <a class="btn secondary" href="{{ route('workflow.test') }}">API 測試</a>
            </div>
        </div>
    </nav>

    <main class="main">
        <div class="container">

            <!-- Step 1: 入口 QR -->
            <div class="step-card active" id="step1">
                <div class="step-header">
                    <div class="step-num">1</div>
                    <div class="step-title">入口 QR 碼</div>
                </div>
                <div class="step-content">
                    <p class="step-desc">掃描 QR 碼進入體驗，或直接開始</p>
                    <div class="qr-box">
                        <h3>歡迎使用個人風格分析</h3>
                        <p>掃描 QR 碼或點擊下方按鈕開始</p>
                        <div id="entryQR" style="margin:16px 0"></div>
                        <button class="btn" onclick="generateEntryQR()">生成 QR 碼</button>
                        <button class="btn secondary" onclick="startDirectly()" style="margin-left:8px">直接開始</button>
                    </div>
                </div>
            </div>

            <!-- Step 2: 照片上傳 -->
            <div class="step-card" id="step2">
                <div class="step-header">
                    <div class="step-num">2</div>
                    <div class="step-title">上傳照片</div>
                </div>
                <div class="step-content">
                    <p class="step-desc">上傳個人照片建立風格基礎</p>
                    <div class="upload-area" onclick="document.getElementById('photoInput').click()">
                        <div id="photoPreview">
                            <p style="font-size:16px;margin-bottom:8px">📸 點擊上傳照片</p>
                            <p>支援 JPG, PNG</p>
                        </div>
                    </div>
                    <input type="file" id="photoInput" accept="image/*" style="display:none"
                        onchange="handlePhotoUpload()" />
                    <button class="btn" id="uploadBtn" onclick="uploadPhoto()" disabled>確認上傳</button>
                    <div id="uploadResult" class="result-box" style="display:none"></div>
                </div>
            </div>

            <!-- Step 3: 影片情緒 -->
            <div class="step-card" id="step3">
                <div class="step-header">
                    <div class="step-num">3</div>
                    <div class="step-title">影片情緒偵測</div>
                </div>
                <div class="step-content">
                    <p class="step-desc">觀看影片時即時偵測情緒變化</p>
                    <div class="video-box" id="videoPlayer">
                        <div>
                            <h3>🎬 風格時尚介紹影片</h3>
                            <p>點擊開始播放並偵測情緒</p>
                            <button class="btn" onclick="startVideoAnalysis()" style="margin-top:16px">開始播放</button>
                        </div>
                    </div>
                    <div id="emotionTimeline" class="timeline" style="display:none">
                        <h4>情緒時間軸</h4>
                        <div id="emotionPoints"></div>
                    </div>
                    <div id="emotionSummary" class="result-box" style="display:none"></div>
                </div>
            </div>

            <!-- Step 4: 配飾搭配 -->
            <div class="step-card" id="step4">
                <div class="step-header">
                    <div class="step-num">4</div>
                    <div class="step-title">智能配飾搭配</div>
                </div>
                <div class="step-content">
                    <p class="step-desc">基於情緒分析生成配飾建議</p>
                    <div id="accessoriesDisplay" class="grid"></div>
                    <button class="btn" id="accessoryBtn" onclick="generateAccessories()" disabled>生成配飾建議</button>
                </div>
            </div>

            <!-- Step 5: 性格分析 -->
            <div class="step-card" id="step5">
                <div class="step-header">
                    <div class="step-num">5</div>
                    <div class="step-title">角色性格分析</div>
                </div>
                <div class="step-content">
                    <p class="step-desc">綜合分析生成性格原型</p>
                    <div id="characterAnalysis"></div>
                    <button class="btn" id="analysisBtn" onclick="analyzeCharacter()" disabled>分析性格</button>
                </div>
            </div>

            <!-- Step 6: 生成風格形象 -->
            <div class="step-card" id="step6">
                <div class="step-header">
                    <div class="step-num">6</div>
                    <div class="step-title">生成風格形象</div>
                </div>
                <div class="step-content">
                    <p class="step-desc">使用 AI 生成專屬風格形象圖</p>
                    <div id="imageGenArea"></div>
                    <button class="btn" id="imageBtn" onclick="generateStyleImage()" disabled>生成形象圖</button>
                </div>
            </div>

            <!-- Step 7: 輸出檔案 -->
            <div class="step-card" id="step7">
                <div class="step-header">
                    <div class="step-num">7</div>
                    <div class="step-title">個人風格檔案</div>
                </div>
                <div class="step-content">
                    <p class="step-desc">生成完整的個人風格角色檔案</p>
                    <div id="finalProfile"></div>
                    <button class="btn" id="profileBtn" onclick="generateProfile()" disabled>生成檔案</button>
                    <button class="btn success" id="downloadBtn" onclick="downloadProfile()" disabled
                        style="margin-left:8px">下載檔案</button>
                </div>
            </div>

        </div>
    </main>
@endsection

@section('scripts')
    <script>
        const API_DEMO = "{{ url('/api/demo') }}";
        let currentStep = 1;
        let userData = {
            photoUploaded: false,
            emotionData: null,
            accessoryData: null,
            characterData: null,
            styleImageUrl: null
        };

        async function generateEntryQR() {
            try {
                const res = await fetch(API_DEMO, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        step: 'entry_qr'
                    })
                });
                const data = await res.json();
                if (!data.success) throw new Error(data.error || 'QR 生成失敗');
                document.getElementById('entryQR').innerHTML =
                    `<img src="${data.qr_url}" alt="QR碼" style="max-width:180px;border-radius:12px;background:#fff;padding:8px"><p style="margin-top:10px;font-size:14px">${data.message}</p>`;
            } catch (e) {
                alert('QR碼生成失敗：' + e.message);
            }
        }

        function startDirectly() {
            nextStep();
        }

        function handlePhotoUpload() {
            const file = document.getElementById('photoInput').files[0];
            if (file) {
                const r = new FileReader();
                r.onload = e => {
                    document.getElementById('photoPreview').innerHTML =
                        `<img src="${e.target.result}" class="preview-img" alt="預覽"><p style="margin-top:8px">照片預覽</p>`;
                };
                r.readAsDataURL(file);
                document.getElementById('uploadBtn').disabled = false;
            }
        }

        async function uploadPhoto() {
            const btn = document.getElementById('uploadBtn');
            btn.innerHTML = '上傳中<span class="loading"></span>';
            btn.disabled = true;
            try {
                const res = await fetch(API_DEMO, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        step: 'photo_upload'
                    })
                });
                const data = await res.json();
                if (!data.success) throw new Error(data.error || '上傳失敗');
                userData.photoUploaded = true;
                const resultDiv = document.getElementById('uploadResult');
                resultDiv.innerHTML = `<h4>✓ 上傳成功</h4><p>${data.message}</p>`;
                resultDiv.style.display = 'block';
                btn.innerHTML = '上傳完成';
                setTimeout(nextStep, 800);
            } catch (e) {
                alert('上傳失敗：' + e.message);
                btn.innerHTML = '重新上傳';
                btn.disabled = false;
            }
        }

        async function startVideoAnalysis() {
            const videoPlayer = document.getElementById('videoPlayer');
            videoPlayer.innerHTML =
                `<div><h3 style="color:#fff">⏯️ 播放中...</h3><p style="color:#cbd5e1">正在偵測情緒反應</p><div class="loading" style="margin:20px auto"></div></div>`;
            setTimeout(async () => {
                try {
                    const res = await fetch(API_DEMO, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            step: 'video_emotion'
                        })
                    });
                    const data = await res.json();
                    if (!data.success) throw new Error(data.error || '偵測失敗');
                    userData.emotionData = data;
                    const timeline = document.getElementById('emotionTimeline');
                    const points = document.getElementById('emotionPoints');
                    points.innerHTML = data.emotion_sequence.map(it =>
                        `<div class="emotion-item"><strong>${it.time}</strong><span>${getEmotionText(it.emotion)} (${(it.confidence*100).toFixed(0)}%)</span></div>`
                        ).join('');
                    timeline.style.display = 'block';
                    const summary = document.getElementById('emotionSummary');
                    summary.innerHTML =
                        `<h4>✓ 分析完成</h4><p><strong>主導情緒：</strong>${getEmotionText(data.dominant_emotion)}</p><p><strong>總結：</strong>${data.analysis_summary}</p>`;
                    summary.style.display = 'block';
                    videoPlayer.innerHTML =
                        `<div><h3 style="color:#fff">✅ 播放完畢</h3><p style="color:#cbd5e1">情緒偵測已完成</p></div>`;
                    setTimeout(nextStep, 1000);
                } catch (e) {
                    alert('偵測失敗：' + e.message);
                }
            }, 2000);
        }

        async function generateAccessories() {
            const btn = document.getElementById('accessoryBtn');
            btn.innerHTML = '生成中<span class="loading"></span>';
            btn.disabled = true;
            try {
                const res = await fetch(API_DEMO, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        step: 'accessory_matching',
                        emotion: userData.emotionData?.dominant_emotion || 'happy'
                    })
                });
                const data = await res.json();
                if (!data.success) throw new Error(data.error || '搭配失敗');
                userData.accessoryData = data;
                const display = document.getElementById('accessoriesDisplay');
                const label = k => ({
                    hat: '帽子',
                    glasses: '眼鏡',
                    jewelry: '飾品',
                    bag: '包包'
                })[k] || k;
                display.innerHTML = Object.entries(data.accessories).map(([k, v]) =>
                    `<div class="acc-card selected"><h4>${label(k)}</h4><p style="font-weight:600;margin:8px 0">${v}</p><p>${data.styling_concept}</p></div>`
                    ).join('');
                btn.innerHTML = '生成完成';
                setTimeout(nextStep, 800);
            } catch (e) {
                alert('搭配失敗：' + e.message);
                btn.innerHTML = '重新生成';
                btn.disabled = false;
            }
        }

        async function analyzeCharacter() {
            const btn = document.getElementById('analysisBtn');
            btn.innerHTML = '分析中<span class="loading"></span>';
            btn.disabled = true;
            try {
                const res = await fetch(API_DEMO, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        step: 'character_analysis',
                        emotion: userData.emotionData?.dominant_emotion || 'happy',
                        accessories: userData.accessoryData?.accessories || {}
                    })
                });
                const data = await res.json();
                if (!data.success) throw new Error(data.error || '分析失敗');
                userData.characterData = data.character_profile;
                const analysis = document.getElementById('characterAnalysis');
                const prof = userData.characterData;
                analysis.innerHTML = `
    <div class="profile-box">
      <h3>${prof.character_archetype}</h3>
      <h4 style="margin:8px 0;color:#06b6d4">${prof.personality_type}</h4>
      <div class="traits">${prof.traits.map(t => `<div class="trait">${t}</div>`).join('')}</div>
      <h4>風格關鍵字</h4>
      <p style="margin:8px 0;color:#475569">${prof.style_keywords.join(' • ')}</p>
      <h4>專屬色彩</h4>
      <div class="colors">${prof.color_palette.map(c => `<div class="color" style="background:${c}"></div>`).join('')}</div>
      <p style="margin-top:16px;font-style:italic;color:#475569">${data.overall_assessment}</p>
    </div>`;
                btn.innerHTML = '分析完成';
                setTimeout(nextStep, 800);
            } catch (e) {
                alert('分析失敗：' + e.message);
                btn.innerHTML = '重新分析';
                btn.disabled = false;
            }
        }

        async function generateStyleImage() {
            const btn = document.getElementById('imageBtn');
            const area = document.getElementById('imageGenArea');
            btn.innerHTML = '生成中（約 30 秒）<span class="loading"></span>';
            btn.disabled = true;
            area.innerHTML =
                `<div class="image-generating"><h4>🎨 AI 正在生成您的專屬風格形象...</h4><p>這需要約 30 秒時間，請稍候</p><div class="loading" style="margin:20px auto"></div></div>`;
            try {
                const res = await fetch(API_DEMO, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        step: 'generate_style_image',
                        character_data: userData.characterData,
                        emotion: userData.emotionData?.dominant_emotion || 'happy',
                        accessories: userData.accessoryData?.accessories || {}
                    })
                });
                const data = await res.json();
                if (!data.success) throw new Error(data.error || '生成失敗');
                userData.styleImageUrl = data.image_url;
                area.innerHTML =
                    `<div class="result-box"><h4>✨ 風格形象生成成功！</h4><img src="${data.image_url}" alt="風格形象" class="generated-img" /><p style="font-size:13px;color:#64748b;margin-top:8px">Prompt: ${data.prompt}</p></div>`;
                btn.innerHTML = '生成完成';
                setTimeout(nextStep, 1000);
            } catch (e) {
                alert('圖片生成失敗：' + e.message);
                area.innerHTML =
                    `<div class="result-box" style="border-color:#ef4444"><h4 style="color:#ef4444">✗ 生成失敗</h4><p>${e.message}</p></div>`;
                btn.innerHTML = '重新生成';
                btn.disabled = false;
            }
        }

        async function generateProfile() {
            const btn = document.getElementById('profileBtn');
            btn.innerHTML = '生成中<span class="loading"></span>';
            btn.disabled = true;
            try {
                const res = await fetch(API_DEMO, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        step: 'output_profile',
                        character_data: userData.characterData
                    })
                });
                const data = await res.json();
                if (!data.success) throw new Error(data.error || '生成失敗');
                const profile = document.getElementById('finalProfile');
                profile.innerHTML = `
    <div class="profile-box">
      <h3>🎭 個人風格角色檔案</h3>
      <p style="margin:12px 0;color:#475569">檔案編號：${data.character_profile.profile_id}</p>
      ${userData.styleImageUrl ? `<img src="${userData.styleImageUrl}" alt="風格形象" class="generated-img" style="max-width:400px;margin:16px auto" />` : ''}
      <h4>風格建議</h4>
      <ul class="insight-list">${data.character_profile.style_recommendations.map(i => `<li>${i}</li>`).join('')}</ul>
      <h4>個性洞察</h4>
      <ul class="insight-list">${data.character_profile.personality_insights.map(i => `<li>${i}</li>`).join('')}</ul>
      <p style="text-align:center;margin-top:20px;font-size:16px">🎉 您的專屬檔案已生成完成</p>
    </div>`;
                document.getElementById('downloadBtn').disabled = false;
                btn.innerHTML = '生成完成';
                setTimeout(() => {
                    alert('🎊 恭喜！角色檔案已完成');
                }, 500);
            } catch (e) {
                alert('生成失敗：' + e.message);
                btn.innerHTML = '重新生成';
                btn.disabled = false;
            }
        }

        function downloadProfile() {
            const payload = {
                profile_id: userData.characterData ? 'STYLE_' + Date.now() : 'DEMO_PROFILE',
                character_type: userData.characterData?.character_archetype || '演示角色',
                creation_date: new Date().toLocaleString(),
                style_image_url: userData.styleImageUrl,
                emotion_analysis: userData.emotionData,
                style_matching: userData.accessoryData,
                character_profile: userData.characterData
            };
            const blob = new Blob([JSON.stringify(payload, null, 2)], {
                type: 'application/json'
            });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = `風格角色檔案_${new Date().toISOString().slice(0,10)}.json`;
            document.body.appendChild(a);
            a.click();
            a.remove();
        }

        function nextStep() {
            if (currentStep < 7) {
                document.getElementById(`step${currentStep}`).classList.remove('active');
                document.getElementById(`step${currentStep}`).classList.add('completed');
                currentStep++;
                document.getElementById(`step${currentStep}`).classList.add('active');
                enableNextStepButton();
                document.getElementById(`step${currentStep}`).scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        function enableNextStepButton() {
            switch (currentStep) {
                case 4:
                    document.getElementById('accessoryBtn').disabled = false;
                    break;
                case 5:
                    document.getElementById('analysisBtn').disabled = false;
                    break;
                case 6:
                    document.getElementById('imageBtn').disabled = false;
                    break;
                case 7:
                    document.getElementById('profileBtn').disabled = false;
                    break;
            }
        }

        function getEmotionText(e) {
            const m = {
                happy: '開心 😊',
                sad: '難過 😢',
                angry: '憤怒 😠',
                neutral: '平靜 😐',
                surprised: '驚訝 😲'
            };
            return m[e] || e;
        }

        window.addEventListener('load', generateEntryQR);
    </script>
@endsection
