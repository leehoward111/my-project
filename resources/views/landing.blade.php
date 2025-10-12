@extends('layouts.app')

@section('title', 'AI 個人風格分析')

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
            height: 100%;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Noto Sans", Arial, sans-serif;
            position: relative;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            touch-action: manipulation;
        }

        /* 動態背景粒子效果 */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: float 20s infinite;
            will-change: transform, opacity;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) translateX(0) scale(1);
                opacity: 0.3;
            }

            50% {
                transform: translateY(-100px) translateX(50px) scale(1.2);
                opacity: 0.6;
            }
        }

        /* 背景圖片層 */
        .bg-layer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1;
            opacity: 0.3;
        }

        .bg-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            filter: blur(2px);
        }

        /* 漸層遮罩 */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg,
                    rgba(102, 126, 234, 0.85) 0%,
                    rgba(118, 75, 162, 0.85) 50%,
                    rgba(240, 147, 251, 0.85) 100%);
            z-index: 2;
        }

        .fullpage {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            z-index: 3;
        }

        /* Logo/品牌區域 */
        .brand-area {
            padding: 30px 40px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #fff, #f0f9ff);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            animation: pulse 3s ease-in-out infinite;
            flex-shrink: 0;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .brand-text {
            color: #fff;
            font-size: 24px;
            font-weight: 800;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            letter-spacing: 0.5px;
        }

        /* 主內容區 */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            flex-direction: column;
            gap: 40px;
        }

        /* 標題區 */
        .hero-title {
            text-align: center;
            margin-bottom: 20px;
            max-width: 90%;
        }

        .hero-title h1 {
            color: #fff;
            font-size: clamp(32px, 7vw, 56px);
            font-weight: 900;
            margin-bottom: 16px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: -1px;
            animation: slideDown 0.8s ease-out;
            line-height: 1.2;
        }

        .hero-title p {
            color: rgba(255, 255, 255, 0.95);
            font-size: clamp(16px, 3.5vw, 20px);
            font-weight: 500;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.8s ease-out 0.2s both;
            line-height: 1.5;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 卡片容器 */
        .content-wrapper {
            display: flex;
            flex-direction: row;
            gap: 20px;
            align-items: stretch;
            max-width: 1000px;
            width: 90%;
            animation: fadeInUp 0.8s ease-out 0.4s both;
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

        /* 功能卡片 */
        .info-card {
            flex: 1;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 28px 24px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            min-height: 140px;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #fff, rgba(255, 255, 255, 0.5));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .info-card:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .info-card:hover::before {
            opacity: 1;
        }

        .info-card h3 {
            margin: 0 0 12px;
            color: #fff;
            font-weight: 700;
            font-size: clamp(17px, 3.5vw, 20px);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-card h3 span {
            font-size: clamp(24px, 5vw, 28px);
            flex-shrink: 0;
        }

        .info-card p {
            margin: 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: clamp(14px, 2.8vw, 15px);
            line-height: 1.6;
        }

        /* 開始按鈕 */
        .button-group {
            display: flex;
            gap: 16px;
            z-index: 10;
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: #fff;
            color: #667eea;
            border: 0;
            padding: clamp(16px, 3vw, 20px) clamp(36px, 8vw, 50px);
            border-radius: 50px;
            font-weight: 700;
            font-size: clamp(18px, 3.5vw, 20px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            min-width: 200px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn:active {
            transform: scale(0.95);
        }

        .btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        }

        .btn span {
            position: relative;
            z-index: 1;
        }

        /* 底部裝飾波浪 */
        .wave-decoration {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 2;
            pointer-events: none;
        }

        .wave-decoration svg {
            display: block;
            width: 100%;
            height: clamp(60px, 15vw, 100px);
        }

        /* 平板優化 (768px - 1024px) */
        @media (max-width: 1024px) and (min-width: 769px) {
            .brand-area {
                padding: 25px 30px;
            }

            .content-wrapper {
                width: 85%;
                gap: 18px;
            }

            .info-card {
                padding: 26px 22px;
                min-height: 130px;
            }

            .main-content {
                gap: 35px;
                padding: 35px 20px;
            }
        }

        /* 手機橫屏 (小於 768px 但寬高比大於 1.3) */
        @media (max-width: 768px) and (orientation: landscape) {
            .brand-area {
                padding: 15px 20px;
            }

            .brand-icon {
                width: 36px;
                height: 36px;
                font-size: 18px;
            }

            .brand-text {
                font-size: 16px;
            }

            .hero-title {
                margin-bottom: 10px;
            }

            .hero-title h1 {
                margin-bottom: 8px;
            }

            .main-content {
                gap: 20px;
                padding: 20px;
            }

            .content-wrapper {
                flex-direction: row;
                gap: 12px;
                width: 95%;
            }

            .info-card {
                padding: 16px 14px;
                min-height: auto;
            }

            .info-card h3 {
                margin-bottom: 6px;
                gap: 6px;
            }

            .btn {
                padding: 12px 28px;
                min-width: 160px;
            }
        }

        /* 手機直立 (小於 768px) */
        @media (max-width: 768px) {
            .particles {
                display: none;
                /* 手機上隱藏粒子動畫，提升效能 */
            }

            .brand-area {
                padding: 20px;
            }

            .brand-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }

            .brand-text {
                font-size: 18px;
            }

            .main-content {
                gap: 30px;
                padding: 30px 15px;
            }

            .hero-title {
                margin-bottom: 15px;
            }

            .content-wrapper {
                flex-direction: column;
                width: 95%;
                max-width: none;
                gap: 16px;
            }

            .info-card {
                padding: 24px 20px;
                min-height: 110px;
            }

            .info-card:active {
                transform: scale(0.98);
            }

            .btn {
                min-width: 180px;
            }
        }

        /* 超小手機 (小於 375px) */
        @media (max-width: 374px) {
            .brand-area {
                padding: 15px;
            }

            .brand-icon {
                width: 36px;
                height: 36px;
                font-size: 18px;
            }

            .brand-text {
                font-size: 16px;
            }

            .main-content {
                gap: 25px;
                padding: 25px 12px;
            }

            .content-wrapper {
                width: 98%;
                gap: 14px;
            }

            .info-card {
                padding: 20px 16px;
                min-height: 100px;
            }

            .info-card h3 {
                gap: 6px;
            }

            .btn {
                min-width: 160px;
            }
        }

        /* 超大螢幕優化 (大於 1920px) */
        @media (min-width: 1921px) {
            .content-wrapper {
                max-width: 1200px;
                gap: 24px;
            }

            .info-card {
                padding: 32px 28px;
                min-height: 160px;
            }

            .brand-area {
                padding: 40px 50px;
            }

            .brand-icon {
                width: 56px;
                height: 56px;
                font-size: 28px;
            }

            .brand-text {
                font-size: 28px;
            }
        }

        /* 確保在所有裝置上觸控友善 */
        @media (hover: none) and (pointer: coarse) {
            .btn:hover {
                transform: none;
            }

            .btn:active {
                transform: scale(0.95);
            }

            .info-card:hover {
                transform: none;
            }

            .info-card:active {
                transform: scale(0.98);
            }
        }
    </style>
@endsection

@section('content')
    <!-- 動態粒子背景 -->
    <div class="particles" id="particles"></div>

    <!-- 背景圖片層 -->
    <div class="bg-layer">
        <img src="{{ asset('images/hero-image.jpg') }}" alt="背景" class="bg-image">
    </div>

    <!-- 漸層遮罩 -->
    <div class="overlay"></div>

    <!-- 波浪裝飾 -->
    <div class="wave-decoration">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,50 C300,100 600,0 900,50 C1050,75 1150,50 1200,50 L1200,120 L0,120 Z" fill="rgba(255,255,255,0.1)">
            </path>
        </svg>
    </div>

    <!-- 前景內容 -->
    <div class="fullpage">
        <!-- 品牌區 -->
        <div class="brand-area">
            <div class="brand-icon">✨</div>
            <div class="brand-text">AI 個人風格分析</div>
        </div>

        <!-- 主內容 -->
        <div class="main-content">
            <!-- 標題 -->
            <div class="hero-title">
                <h1>探索你的專屬風格</h1>
                <p>透過 AI 情緒分析，打造獨一無二的個人形象</p>
            </div>

            <!-- 功能卡片 -->
            <div class="content-wrapper">
                <div class="info-card">
                    <h3><span>🎭</span> <span>情緒分析</span></h3>
                    <p>偵測影片觀看過程的情緒序列，萃取主導情緒與趨勢</p>
                </div>

                <div class="info-card">
                    <h3><span>👗</span> <span>智能搭配</span></h3>
                    <p>依主導情緒生成配飾建議與風格概念，快速形成穿搭方向</p>
                </div>

                <div class="info-card">
                    <h3><span>📄</span> <span>角色檔案</span></h3>
                    <p>輸出含關鍵色盤、關鍵字與個性簡述的角色檔案與 QR 分享</p>
                </div>
            </div>

            <!-- 開始按鈕 -->
            <div class="button-group">
                <a class="btn" href="{{ route('demo') }}">
                    <span>開始體驗</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        // 生成動態粒子（僅在桌面版）
        if (window.innerWidth > 768) {
            const particlesContainer = document.getElementById('particles');
            const particleCount = window.innerWidth > 1200 ? 30 : 15;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';

                const size = Math.random() * 60 + 20;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 15) + 's';

                particlesContainer.appendChild(particle);
            }
        }

        // 防止雙擊縮放（iOS Safari）
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function (event) {
            const now = Date.now();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);
    </script>
@endsection