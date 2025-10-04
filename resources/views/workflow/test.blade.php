@extends('layouts.app')

@section('title', 'API 測試')

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
            max-width: 1120px;
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
            height: 64px
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

        .page-header {
            margin-bottom: 32px
        }

        .page-header h1 {
            font-size: 32px;
            margin: 0 0 8px;
            color: #0f172a
        }

        .page-header p {
            color: #64748b;
            margin: 0
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin: 32px 0 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            color: #0f172a
        }

        .test-grid {
            display: grid;
            gap: 16px
        }

        .test-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: .2s
        }

        .test-card:hover {
            border-color: rgba(124, 58, 237, .3);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08)
        }

        .test-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 16px
        }

        .test-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 4px;
            color: #0f172a
        }

        .test-desc {
            color: #64748b;
            font-size: 14px;
            margin: 0
        }

        .result-box {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
            padding: 16px;
            margin-top: 12px;
            display: none
        }

        .result-box.show {
            display: block
        }

        .result-box pre {
            margin: 0;
            color: #475569;
            font-size: 13px;
            line-height: 1.6;
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word
        }

        .result-box img {
            max-width: 100%;
            border-radius: 8px;
            margin: 12px 0;
            display: block;
            border: 2px solid rgba(124, 58, 237, .2)
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 8px;
            background: rgba(226, 232, 240, 0.8);
            color: #475569
        }

        .status.loading {
            background: rgba(6, 182, 212, .15);
            color: #0891b2
        }

        .status.success {
            background: rgba(34, 197, 94, .15);
            color: #16a34a
        }

        .status.error {
            background: rgba(239, 68, 68, .15);
            color: #dc2626
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #7c3aed, #06b6d4);
            font-size: 18px;
            margin-bottom: 8px
        }
    </style>
@endsection

@section('content')
    <nav>
        <div class="container nav-inner">
            <div class="brand">
                <div class="brand-badge"></div>
                <div>AI 個人風格分析</div>
            </div>
            <div class="links">
                <a class="btn secondary" href="{{ route('landing') }}">首頁</a>
                <a class="btn" href="{{ route('demo') }}">開始</a>
            </div>
        </div>
    </nav>

    <main class="main">
        <div class="container">
            <div class="page-header">
                <h1>API 測試面板</h1>
                <p>測試系統所有 API 端點（包含 fal.ai 生圖功能）</p>
            </div>

            <!-- fal.ai 測試區 -->
            <h2 class="section-title">🎨 fal.ai 圖片生成測試</h2>
            <div class="test-grid">
                <div class="test-card">
                    <div class="test-header">
                        <div>
                            <div class="badge">🔌</div>
                            <h3 class="test-title">連接測試</h3>
                            <p class="test-desc">測試 fal.ai API 連接狀態</p>
                        </div>
                        <button class="btn" onclick="testFalConnection(this)">測試連接</button>
                    </div>
                    <div class="result-box" id="result-fal-test"></div>
                </div>

                <div class="test-card">
                    <div class="test-header">
                        <div>
                            <div class="badge">📋</div>
                            <h3 class="test-title">可用模型</h3>
                            <p class="test-desc">列出 fal.ai 可用的生成模型</p>
                        </div>
                        <button class="btn" onclick="testFalModels(this)">查看模型</button>
                    </div>
                    <div class="result-box" id="result-fal-models"></div>
                </div>

                <div class="test-card">
                    <div class="test-header">
                        <div>
                            <div class="badge">🖼️</div>
                            <h3 class="test-title">風格圖片生成</h3>
                            <p class="test-desc">測試基本圖片生成功能</p>
                        </div>
                        <button class="btn" onclick="testStyleImage(this)">生成圖片</button>
                    </div>
                    <div class="result-box" id="result-style-image"></div>
                </div>

                <div class="test-card">
                    <div class="test-header">
                        <div>
                            <div class="badge">👤</div>
                            <h3 class="test-title">角色形象生成</h3>
                            <p class="test-desc">基於角色檔案生成風格形象</p>
                        </div>
                        <button class="btn" onclick="testCharacterImage(this)">生成角色圖</button>
                    </div>
                    <div class="result-box" id="result-character-image"></div>
                </div>
            </div>

            <!-- Demo API 測試區 -->
            <h2 class="section-title">🔄 Demo 流程 API 測試</h2>
            <div class="test-grid">
                <div class="test-card">
                    <div class="test-header">
                        <div>
                            <div class="badge">📱</div>
                            <h3 class="test-title">1. 入口 QR</h3>
                            <p class="test-desc">生成體驗入口 QR Code</p>
                        </div>
                        <button class="btn" onclick="testStep('entry_qr', this)">測試</button>
                    </div>
                    <div class="result-box" id="result-entry_qr"></div>
                </div>

                <div class="test-card">
                    <div class="test-header">
                        <div>
                            <div class="badge">📸</div>
                            <h3 class="test-title">2. 照片上傳</h3>
                            <p class="test-desc">上傳照片建立基礎向量</p>
                        </div>
                        <button class="btn" onclick="testStep('photo_upload', this)">測試</button>
                    </div>
                    <div class="result-box" id="result-photo_upload"></div>
                </div>

                <div class="test-card">
                    <div class="test-header">
                        <div>
                            <div class="badge">🎬</div>
                            <h3 class="test-title">3. 影片情緒</h3>
                            <p class="test-desc">觀看影片即時情緒偵測</p>
                        </div>
                        <button class="btn" onclick="testStep('video_emotion', this)">測試</button>
                    </div>
                    <div class="result-box" id="result-video_emotion"></div>
                </div>

                <div class="test-card">
                    <div class="test-header">
                        <div>
                            <div class="badge">👔</div>
                            <h3 class="test-title">4. 配飾搭配</h3>
                            <p class="test-desc">智能生成配飾建議</p>
                        </div>
                        <button class="btn" onclick="testStep('accessory_matching', this)">測試</button>
                    </div>
                    <div class="result-box" id="result-accessory_matching"></div>
                </div>

                <div class="test-card">
                    <div class="test-header">
                        <div>
                            <div class="badge">🧠</div>
                            <h3 class="test-title">5. 性格分析</h3>
                            <p class="test-desc">萃取性格原型與關鍵字</p>
                        </div>
                        <button class="btn" onclick="testStep('character_analysis', this)">測試</button>
                    </div>
                    <div class="result-box" id="result-character_analysis"></div>
                </div>

                <div class="test-card">
                    <div class="test-header">
                        <div>
                            <div class="badge">📋</div>
                            <h3 class="test-title">6. 輸出檔案</h3>
                            <p class="test-desc">生成完整風格檔案</p>
                        </div>
                        <button class="btn" onclick="testStep('output_profile', this)">測試</button>
                    </div>
                    <div class="result-box" id="result-output_profile"></div>
                </div>

                <div class="test-card">
                    <div class="test-header">
                        <div>
                            <div class="badge">✨</div>
                            <h3 class="test-title">7. 生成風格圖</h3>
                            <p class="test-desc">使用 fal.ai 生成角色形象</p>
                        </div>
                        <button class="btn" onclick="testGenerateImage(this)">測試</button>
                    </div>
                    <div class="result-box" id="result-generate-image"></div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // fal.ai 測試函數
        async function testFalConnection(btn) {
            const resultBox = document.getElementById('result-fal-test');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.innerHTML = '<span class="status loading">⏳ 測試中</span>';
            resultBox.classList.remove('show');
            try {
                const response = await fetch('/api/fal/test', {
                    method: 'GET'
                });
                const data = await response.json();
                let html = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                if (data.success && data.test_image) {
                    html += '<img src="' + data.test_image + '" alt="測試圖片" />';
                }
                resultBox.innerHTML = html;
                resultBox.classList.add('show');
                btn.innerHTML = '<span class="status success">✓ 成功</span>';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 2000);
            } catch (error) {
                resultBox.innerHTML = '<pre style="color:#ef4444">錯誤: ' + error.message + '</pre>';
                resultBox.classList.add('show');
                btn.innerHTML = '<span class="status error">✗ 失敗</span>';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 2000);
            }
        }

        async function testFalModels(btn) {
            const resultBox = document.getElementById('result-fal-models');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.innerHTML = '<span class="status loading">⏳ 查詢中</span>';
            resultBox.classList.remove('show');
            try {
                const response = await fetch('/api/fal/models', {
                    method: 'GET'
                });
                const data = await response.json();
                resultBox.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                resultBox.classList.add('show');
                btn.innerHTML = '<span class="status success">✓ 成功</span>';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 2000);
            } catch (error) {
                resultBox.innerHTML = '<pre style="color:#ef4444">錯誤: ' + error.message + '</pre>';
                resultBox.classList.add('show');
                btn.innerHTML = '<span class="status error">✗ 失敗</span>';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 2000);
            }
        }

        async function testStyleImage(btn) {
            const resultBox = document.getElementById('result-style-image');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.innerHTML = '<span class="status loading">⏳ 生成中（約30秒）</span>';
            resultBox.classList.remove('show');
            try {
                const response = await fetch('/api/fal/generate-style-image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        prompt: 'A stylish portrait of a modern person, clean aesthetic, professional photography',
                        image_size: 'square_hd',
                        num_images: 1,
                    })
                });
                const data = await response.json();
                let html = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                if (data.success && data.images && data.images[0]) {
                    html += '<img src="' + data.images[0].url + '" alt="生成的圖片" />';
                }
                resultBox.innerHTML = html;
                resultBox.classList.add('show');
                btn.innerHTML = '<span class="status success">✓ 成功</span>';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 2000);
            } catch (error) {
                resultBox.innerHTML = '<pre style="color:#ef4444">錯誤: ' + error.message + '</pre>';
                resultBox.classList.add('show');
                btn.innerHTML = '<span class="status error">✗ 失敗</span>';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 2000);
            }
        }

        async function testCharacterImage(btn) {
            const resultBox = document.getElementById('result-character-image');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.innerHTML = '<span class="status loading">⏳ 生成中（約30秒）</span>';
            resultBox.classList.remove('show');
            try {
                const response = await fetch('/api/fal/generate-character-image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        character_archetype: '探索者',
                        personality_type: 'ENFP',
                        style_keywords: ['清新', '輕盈', '機能'],
                        color_palette: ['#7c3aed', '#06b6d4', '#f59e0b'],
                        emotion: 'happy',
                    })
                });
                const data = await response.json();
                let html = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                if (data.success && data.image_url) {
                    html += '<img src="' + data.image_url + '" alt="角色形象" />';
                }
                resultBox.innerHTML = html;
                resultBox.classList.add('show');
                btn.innerHTML = '<span class="status success">✓ 成功</span>';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 2000);
            } catch (error) {
                resultBox.innerHTML = '<pre style="color:#ef4444">錯誤: ' + error.message + '</pre>';
                resultBox.classList.add('show');
                btn.innerHTML = '<span class="status error">✗ 失敗</span>';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 2000);
            }
        }

        // Demo API 測試函數
        async function testStep(step, btn) {
            const resultBox = document.getElementById('result-' + step);
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.innerHTML = '<span class="status loading">⏳ 測試中</span>';
            resultBox.classList.remove('show');
            try {
                const response = await fetch('/api/demo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        step
                    })
                });
                const data = await response.json();
                resultBox.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                resultBox.classList.add('show');
                btn.innerHTML = '<span class="status success">✓ 成功</span>';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 2000);
            } catch (error) {
                resultBox.innerHTML = '<pre style="color:#ef4444">錯誤: ' + error.message + '</pre>';
                resultBox.classList.add('show');
                btn.innerHTML = '<span class="status error">✗ 失敗</span>';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 2000);
            }
        }

        async function testGenerateImage(btn) {
            const resultBox = document.getElementById('result-generate-image');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.innerHTML = '<span class="status loading">⏳ 生成中（約30秒）</span>';
            resultBox.classList.remove('show');
            try {
                const response = await fetch('/api/demo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        step: 'generate_style_image',
                        character_data: {
                            character_archetype: '探索者',
                            style_keywords: ['清新', '輕盈', '機能'],
                        },
                        emotion: 'happy',
                        accessories: {
                            hat: '棒球帽（淺灰）',
                            glasses: '輕薄金屬框',
                        }
                    })
                });
                const data = await response.json();
                let html = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                if (data.success && data.image_url) {
                    html += '<img src="' + data.image_url + '" alt="生成的風格圖" />';
                }
                resultBox.innerHTML = html;
                resultBox.classList.add('show');
                btn.innerHTML = '<span class="status success">✓ 成功</span>';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 2000);
            } catch (error) {
                resultBox.innerHTML = '<pre style="color:#ef4444">錯誤: ' + error.message + '</pre>';
                resultBox.classList.add('show');
                btn.innerHTML = '<span class="status error">✗ 失敗</span>';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 2000);
            }
        }
    </script>
@endsection
