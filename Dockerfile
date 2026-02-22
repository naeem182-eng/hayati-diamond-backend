FROM php:8.2-apache

# ================================
# 1) Install system dependencies + Node.js
# ================================
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev \
    curl gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

# ================================
# 2) Set Apache document root to /public
# ================================
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# ================================
# 3) Copy project files FIRST
# ================================
COPY . .

# ================================
# 4) Install Composer
# ================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

# ================================
# 5) Build Vite assets
# ================================
RUN npm install && npm run build

# ================================
# 6) Fix permissions
# ================================
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 10000

# ================================
# 7) Start container (NO migrate here)
# ================================
CMD php artisan config:cache && \
    php artisan route:cache && \
    sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf && \
    apache2-foreground
