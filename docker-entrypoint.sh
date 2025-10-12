#!/usr/bin/env bash
set -euo pipefail

echo "========================================"
echo "🔍 環境變數（節錄）"
echo "========================================"
echo "DATABASE_URL: ${DATABASE_URL:+${DATABASE_URL:0:30}...}"
echo "DB_CONNECTION: ${DB_CONNECTION:-}"
echo "APP_ENV: ${APP_ENV:-}"
echo "APP_DEBUG: ${APP_DEBUG:-}"
echo "========================================"

# 確保 Apache 監聽 Render 注入的 $PORT
sed -i "s/Listen 80/Listen ${PORT:-80}/" /etc/apache2/ports.conf || true
sed -i "s/:80/:${PORT:-80}/" /etc/apache2/sites-available/000-default.conf || true

# 檢查必要 PHP 擴展
echo "🔍 檢查 PHP 擴展..."
php -m | grep -i pdo || { echo "❌ 缺少 PDO"; exit 1; }
php -m | grep -i pgsql || { echo "❌ 缺少 pgsql/pdo_pgsql"; exit 1; }

cd /var/www/html

# 權限（避免寫入問題）
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 755 storage bootstrap/cache || true

# （可選）若未提供 APP_KEY，可自動產生；正式建議在 Dashboard 設定
# if [ -z "${APP_KEY:-}" ]; then php artisan key:generate --force; fi

echo "🧹 清理舊快取..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "🔗 建立 storage 連結（如已存在略過）..."
php artisan storage:link || true

echo "🗄️ 執行資料庫遷移..."
php artisan migrate --force

echo "🌱 匯入資料（若無 seeder 會自動略過）..."
php artisan db:seed --force || true

echo "⚡ 建立快取（以執行階段環境變數為準）..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🚀 啟動 Apache（port=${PORT:-80}）"
exec apache2-foreground
