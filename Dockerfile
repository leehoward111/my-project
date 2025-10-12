FROM php:8.2-apache

# 安裝系統依賴
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

# 清理快取
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 安裝 PHP 擴展
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# 安裝 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 設定工作目錄
WORKDIR /var/www/html

# 複製應用程式檔案
COPY . /var/www/html

# 安裝 PHP 依賴
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 設定權限
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 設定 Apache DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 啟用 Apache mod_rewrite
RUN a2enmod rewrite

# 執行構建腳本
RUN bash render-build.sh

# 複製並設定 entrypoint 腳本
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 暴露 port  
EXPOSE 80

# 使用 entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
