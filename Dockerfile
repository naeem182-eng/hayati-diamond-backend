FROM php:8.2-apache

# ================================
# 1. Install System Dependencies + Node.js 20
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
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# ================================
# 2. Install Composer
# ================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# copy composer files first (better cache)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# ================================
# 3. Install NPM Dependencies
# ================================
COPY package*.json ./
RUN npm install

# ================================
# 4. Copy Application Files
# ================================
COPY . .

# ================================
# 5. Build Vite Assets
# ================================
RUN npm run build

# ================================
# 6. Permissions
# ================================
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 10000

# ================================
# 7. Start Command (Render)
# ================================
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf && \
    apache2-foreground
