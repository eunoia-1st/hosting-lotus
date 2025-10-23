# Gunakan PHP 8.3 dengan Apache
FROM php:8.3-apache

# Install ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev zip curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer (dari image resmi)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Salin semua file proyek ke dalam container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Generate APP_KEY (kalau belum)
RUN php artisan key:generate || true

# Set permission folder penting
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 untuk web
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
