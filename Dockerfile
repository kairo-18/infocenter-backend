FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libicu-dev \
    libzip-dev  # ✅ Fix for zip extension

# Install PHP Extensions
RUN docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql gettext mysqli intl zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Laravel app
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www /var/www/storage /var/www/bootstrap/cache

# Expose php-fpm port
EXPOSE 9000

CMD ["php-fpm"]
