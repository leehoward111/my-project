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
            background: #4a9fd8;
            animation: backgroundShift 10s ease infinite alternate;
        }

        @keyframes backgroundShift {
            0% {
                background: #4a9fd8;
            }

            100% {
                background: #5aadea;
            }
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
            object-position: center;
            filter: brightness(1.05) contrast(1.1);
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

        /* 半透明遮罩 - 加強玻璃質感 */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg,
                    rgba(240, 249, 255, 0.15) 0%,
                    rgba(224, 242, 254, 0.15) 100%);
            z-index: 1;
            animation: overlayPulse 8s ease-in-out infinite alternate;
        }

        @keyframes overlayPulse {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0.8;
            }
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
            gap: 20px;
            align-items: stretch;
            max-width: 900px;
            width: 100%;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 文字卡片 - 加強玻璃效果和陰影 */
        .info-card {
            flex: 1;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            padding: 24px 20px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12),
                0 2px 8px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            min-height: 140px;
        }

        /* 卡片內光暈效果 */
        .info-card::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.5s;
            pointer-events: none;
        }

        .info-card:hover::after {
            opacity: 1;
        }

        /* Hover 效果 */
        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #7c3aed, #06b6d4);
            border-radius: 16px 16px 0 0;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18),
                0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .info-card:hover::before {
            opacity: 1;
        }

        .info-card h3 {
            margin: 0 0 10px;
            color: #0f172a;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.3px;
        }

        .info-card h3::before {
            content: attr(data-icon);
            font-size: 1.6rem;
            display: inline-block;
            transition: transform 0.3s;
        }

        .info-card:hover h3 {
            color: #7c3aed;
            transform: translateX(2px);
        }

        .info-card:hover h3::before {
            transform: scale(1.2) rotate(5deg);
        }

        .info-card p {
            margin: 0;
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
            transition: color 0.3s;
            font-weight: 400;
        }

        .info-card:hover p {
            color: #475569;
        }

        /* 右下角按鈕組 */
        .button-group {
            position: fixed;
            right: 50%;
            /* ← 改：置中定位 */
            bottom: 8%;
            transform: translateX(50%);
            /* ← 新增：真正置中 */
            display: flex;
            gap: 14px;
            z-index: 10;
            animation: bounceIn 1s ease-out 0.5s both;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #7c3aed, #06b6d4);
            color: #fff;
            border: 0;
            padding: 50px 100px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 2rem;
            box-shadow: 0 8px 24px rgba(124, 58, 237, 0.4),
                0 4px 12px rgba(6, 182, 212, 0.3),
                0 0 0 0 rgba(124, 58, 237, 0.4);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            position: relative;
            overflow: hidden;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 8px 24px rgba(124, 58, 237, 0.4),
                    0 4px 12px rgba(6, 182, 212, 0.3),
                    0 0 0 0 rgba(124, 58, 237, 0.4);
            }

            50% {
                box-shadow: 0 8px 24px rgba(124, 58, 237, 0.4),
                    0 4px 12px rgba(6, 182, 212, 0.3),
                    0 0 0 20px rgba(124, 58, 237, 0);
            }
        }

        /* 按鈕光澤效果 */
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.3),
                    transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn.secondary {
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid #e2e8f0;
            color: #475569;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12),
                0 2px 6px rgba(0, 0, 0, 0.08);
            padding: 14px 24px;
            font-size: 0.95rem;
            font-weight: 600
        }

        .btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 32px rgba(124, 58, 237, 0.5),
                0 6px 16px rgba(6, 182, 212, 0.4),
                0 0 0 0 rgba(124, 58, 237, 0);
            animation: none;
        }

        .btn.secondary:hover {
            background: #fff;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15),
                0 3px 8px rgba(0, 0, 0, 0.1);
        }

        /* 響應式 - 平板 */
        @media (max-width: 1024px) {
            .content-wrapper {
                width: 80%;
                gap: 18px;
                max-width: 750px
            }

            .info-card {
                padding: 22px 18px;
                border-radius: 14px;
                min-height: 130px;
            }

            .info-card h3 {
                font-size: 1rem;
            }

            .info-card h3::before {
                font-size: 1.5rem;
            }

            .info-card p {
                font-size: 0.9rem;
            }

            .button-group {
                right: 30%;
                bottom: 25%
            }

            .btn {
                padding: 16px 28px;
                font-size: 1rem;
                border-radius: 14px;
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
                width: 90%;
                max-width: none;
                gap: 16px;
            }

            .info-card {
                padding: 24px 20px;
                border-radius: 14px;
                min-height: 120px;
            }

            .info-card h3 {
                font-size: 1.05rem;
            }

            .info-card h3::before {
                font-size: 1.5rem;
            }

            .info-card p {
                font-size: 0.9rem;
                line-height: 1.5;
            }

            .button-group {
                right: 50%;
                /* ← 改：置中 */
                bottom: 30px;
                transform: translateX(50%);
                /* ← 新增：真正置中 */
                flex-direction: column;
                width: auto;
                /* ← 新增 */
            }

            .btn {
                padding: 16px 32px;
                /* ← 調整：更適合手機 */
                font-size: 1.1rem;
                border-radius: 12px;
                width: auto;
                /* ← 新增：避免過寬 */
                white-space: nowrap;
                /* ← 新增：文字不換行 */
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