@extends('layouts.app')

@section('title', 'AI 個人風格分析')

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
            height: 100%;
            overflow: hidden
        }

        body {
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Noto Sans", Arial;
            position: relative;
            background: #4a9fd8
        }

        /* 背景圖片層 - 填滿整個視窗 */
        .bg-layer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0
        }

        .bg-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center
        }

        /* 底部藍色填充 */
        .bg-layer::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 30%;
            background: linear-gradient(to top, #4a9fd8 0%, transparent 100%);
            pointer-events: none
        }

        /* 半透明遮罩 */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(240, 249, 255, 0.2) 0%, rgba(224, 242, 254, 0.2) 100%);
            z-index: 1
        }

        .fullpage {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            z-index: 2
        }

        /* 主內容區 */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px
        }

        /* 橫向排列的內容區 */
        .content-wrapper {
            display: flex;
            flex-direction: row;
            gap: 16px;
            align-items: stretch;
            max-width: 700px;
            width: 100%
        }

        /* 文字卡片 */
        .info-card {
            flex: 1;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            padding: 16px;
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.5);
            display: flex;
            flex-direction: column;
            justify-content: center
        }

        .info-card h3 {
            margin: 0 0 6px;
            color: #0f172a;
            font-weight: 700;
            font-size: 0.8rem
        }

        .info-card p {
            margin: 0;
            color: #64748b;
            font-size: 0.7rem;
            line-height: 1.3
        }

        /* 右下角按鈕組 */
        .button-group {
            position: fixed;
            right: 20%;
            bottom: 5%;
            display: flex;
            gap: 14px;
            z-index: 10
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #7c3aed, #06b6d4);
            color: #fff;
            border: 0;
            padding: 50px 100px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 2rem;
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.5);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none
        }

        .btn.secondary {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #e2e8f0;
            color: #475569;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 14px 24px;
            font-size: 0.95rem;
            font-weight: 600
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.5)
        }

        .btn.secondary:hover {
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15)
        }

        /* 響應式 - 平板 */
        @media (max-width: 1024px) {
            .content-wrapper {
                width: 70%;
                gap: 14px;
                max-width: 600px
            }

            .info-card {
                padding: 16px
            }

            .button-group {
                right: 30%;
                bottom: 25%
            }

            .btn {
                padding: 16px 28px;
                font-size: 1rem
            }

            .btn.secondary {
                padding: 12px 20px;
                font-size: 0.9rem
            }
        }

        /* 響應式 - 手機 */
        @media (max-width: 768px) {
            .content-wrapper {
                flex-direction: column;
                width: 85%;
                max-width: none
            }

            .info-card {
                padding: 18px
            }

            .info-card h3 {
                font-size: 0.95rem
            }

            .info-card p {
                font-size: 0.8rem;
                line-height: 1.4
            }

            .button-group {
                right: 20px;
                bottom: 20px;
                flex-direction: column
            }

            .btn {
                padding: 14px 24px;
                font-size: 1rem
            }

            .btn.secondary {
                padding: 12px 20px;
                font-size: 0.85rem
            }
        }
    </style>
@endsection

@section('content')
    <!-- 背景圖片層 -->
    <div class="bg-layer">
        <img src="{{ asset('images/hero-image.jpg') }}" alt="背景" class="bg-image">
    </div>

    <!-- 半透明遮罩 -->
    <div class="overlay"></div>

    <!-- 前景內容 -->
    <div class="fullpage">
        <!-- 主內容 -->
        <div class="main-content">
            <div class="content-wrapper">
                <!-- 情緒分析 -->
                <div class="info-card">
                    <h3>🎭 情緒分析</h3>
                    <p>偵測影片觀看過程的情緒序列，萃取主導情緒與趨勢</p>
                </div>

                <!-- 智能搭配 -->
                <div class="info-card">
                    <h3>👗 智能搭配</h3>
                    <p>依主導情緒生成配飾建議與風格概念，快速形成穿搭方向</p>
                </div>

                <!-- 角色檔案 -->
                <div class="info-card">
                    <h3>📄 角色檔案</h3>
                    <p>輸出含關鍵色盤、關鍵字與個性簡述的角色檔案與 QR 分享</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 右下角按鈕 -->
    <div class="button-group">
        <a class="btn" href="{{ route('demo') }}">開始體驗</a>
    </div>
@endsection