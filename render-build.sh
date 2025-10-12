#!/usr/bin/env bash
# exit on error
set -o errexit

echo "🚀 開始構建 Laravel 專案..."

# 安裝 Composer 依賴
echo "📦 安裝 Composer 依賴..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# 清除快取
echo "🧹 清除舊快取..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 建立快取（生產環境優化）
echo "⚡ 建立快取..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 執行資料庫遷移
echo "🗄️  執行資料庫遷移..."
php artisan migrate --force

# 執行 Seeder（匯入 81 筆 outfit_formulas 資料）
echo "🌱 匯入資料..."
php artisan db:seed --force

echo "✅ 構建完成！"