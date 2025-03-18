# Menggunakan image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan CI4
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql zip

# Aktifkan mod_rewrite untuk Apache
RUN a2enmod rewrite

# Set direktori kerja
WORKDIR /var/www/html

# Copy file proyek CI4 ke dalam container
COPY . /var/www/html

# Ubah izin file dan kepemilikan
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/writable

# Expose port 6060
EXPOSE 6060

# Konfigurasi Apache untuk listen di port 6060
RUN sed -i 's/Listen 80/Listen 6060/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:6060>/' /etc/apache2/sites-enabled/000-default.conf

# Jalankan Apache
CMD ["apache2-foreground"]
