FROM php:8.2-apache

# --------------- 系統依賴 ---------------
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    libpq-dev postgresql-client \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# --------------- PHP 擴展（PostgreSQL 等）---------------
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
 && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# --------------- Composer ---------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# --------------- Apache 設定（DocumentRoot 指向 public/）---------------
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
 && a2enmod rewrite

# --------------- 專案檔案（先 composer 檔 → 安裝依賴）---------------
WORKDIR /var/www/html
COPY composer.json composer.lock ./

# build 階段安裝 production 依賴（不執行 artisan）
COPY render-build.sh /usr/local/bin/render-build.sh
RUN chmod +x /usr/local/bin/render-build.sh \
 && /usr/local/bin/render-build.sh

# 之後再複製全專案（避免每次程式改動就重裝依賴）
COPY . .

# 產生 optimized autoload（仍不跑 artisan）
RUN composer dump-autoload --optimize --no-dev

# 權限（Apache 使用 www-data）
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html/storage \
 && chmod -R 755 /var/www/html/bootstrap/cache

# 讓 Apache 監聽 Render 注入的 $PORT
RUN sed -i 's/Listen 80/Listen ${PORT:-80}/' /etc/apache2/ports.conf

# 複製並設定 entrypoint（啟動時才做 migrate/cache）
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 預設環境變數（實際會被 Render 覆蓋）
ENV DATABASE_URL=""
ENV DB_CONNECTION="pgsql"
ENV APP_ENV="production"
ENV APP_DEBUG="false"

EXPOSE ${PORT:-80}
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
