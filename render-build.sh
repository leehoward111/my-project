#!/usr/bin/env bash
set -o errexit

echo "🚀 開始構建 Laravel 專案..."

# 清除快取
echo "🧹 清除舊快取..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# 建立快取（生產環境優化）
echo "⚡ 建立快取..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ 構建完成！"
echo "⚠️  注意：Migration 和 Seeder 將在容器啟動時執行"
