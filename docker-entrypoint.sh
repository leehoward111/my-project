#!/bin/bash
set -e

echo "🗄️  執行資料庫遷移..."
php artisan migrate --force

echo "🌱 匯入資料..."
php artisan db:seed --force

echo "✅ 資料庫設定完成！"
echo "🚀 啟動 Apache..."

# 執行原本的 CMD
exec apache2-foreground
