#!/bin/bash
set -e

echo "🔍 檢查 PHP 擴展..."
php -m | grep pdo
php -m | grep pgsql

echo "🔍 檢查資料庫連接..."
php artisan db:show || echo "Database connection check failed, but continuing..."

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
