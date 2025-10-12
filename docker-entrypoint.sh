#!/bin/bash
set -e

echo "========================================"
echo "🔍 環境變數除錯"
echo "========================================"
echo "DATABASE_URL: ${DATABASE_URL:0:50}..."
echo "DB_CONNECTION: $DB_CONNECTION"
echo "APP_ENV: $APP_ENV"
echo "PORT: $PORT"
echo "========================================"

echo "🔍 檢查 PHP 擴展..."
php -m | grep pdo
php -m | grep pgsql

echo "🗄️  執行資料庫遷移..."
php artisan migrate --force

echo "🌱 匯入資料..."
php artisan db:seed --force

echo "✅ 資料庫設定完成！"

echo "🚀 啟動 Apache..."
echo "Listening on port ${PORT:-80}"

# 更新 Apache 配置使用環境變數 PORT
sed -i "s/Listen 80/Listen ${PORT:-80}/" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT:-80}/" /etc/apache2/sites-available/000-default.conf

# 啟動 Apache
exec apache2-foreground
