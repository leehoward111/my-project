#!/bin/bash
set -e

echo "========================================"
echo "🔍 環境變數除錯"
echo "========================================"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"
echo "DB_CONNECTION: $DB_CONNECTION"
echo "APP_ENV: $APP_ENV"
echo "========================================"

# 在 runtime 建立快取（這時環境變數已經有了）
echo "⚡ 建立配置快取..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🗄️  執行資料庫遷移..."
php artisan migrate --force

echo "🌱 匯入資料..."
php artisan db:seed --force

echo "✅ 完成！"
echo "🚀 啟動 Apache on port ${PORT:-80}..."

# 更新 Apache 配置
sed -i "s/Listen 80/Listen ${PORT:-80}/" /etc/apache2/ports.conf 2>/dev/null || true
sed -i "s/:80/:${PORT:-80}/" /etc/apache2/sites-available/000-default.conf 2>/dev/null || true

# 啟動 Apache
exec apache2-foreground
