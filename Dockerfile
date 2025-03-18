# Gunakan base image PHP CLI
FROM php:8.2-cli

# Install ekstensi PHP yang dibutuhkan CI4
RUN apt-get update && apt-get install -y \
    unzip curl libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql zip intl

# Set direktori kerja
WORKDIR /var/www/html

# Copy file proyek ke dalam container
COPY . /var/www/html

# Copy file .env dari sistem host jika ada
ARG ENV_FILE=.env
COPY ${ENV_FILE} /var/www/html/.env

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Jalankan composer install
RUN composer install --no-interaction --no-dev --prefer-dist

# Set permission untuk folder writable
RUN chmod -R 777 /var/www/html/writable

# Expose port 6060
EXPOSE 6060

# Jalankan server CI4
CMD ["php", "spark", "serve", "--host=0.0.0.0", "--port=6060"]
