FROM php:8.2-apache

# 安裝系統依賴
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    postgresql-client

# 清理快取
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 安裝 PHP 擴展 - 重要：先安裝 pdo 再安裝 pdo_pgsql
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip

# 驗證 PostgreSQL 擴展已安裝
RUN php -m | grep -i pdo

# 安裝 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 設定工作目錄
WORKDIR /var/www/html

# 複製 composer 檔案
COPY composer.json composer.lock ./

# 安裝 PHP 依賴
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# 複製應用程式檔案
COPY . .

# 完成 Composer 安裝
RUN composer dump-autoload --optimize --no-dev

# 設定權限
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# 設定 Apache DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 啟用 Apache mod_rewrite
RUN a2enmod rewrite

# 設定 Apache 監聽動態 PORT
RUN sed -i 's/Listen 80/Listen ${PORT:-80}/' /etc/apache2/ports.conf

# 執行構建腳本（清除和建立快取）
RUN bash render-build.sh

# 複製並設定 entrypoint 腳本
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 聲明環境變數（這些會在 runtime 時被 Render 注入的值覆蓋）
ENV DATABASE_URL=""
ENV DB_CONNECTION="pgsql"
ENV APP_ENV="production"
ENV APP_DEBUG="false"

# 暴露 port
EXPOSE ${PORT:-80}

# 使用 entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
