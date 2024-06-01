# Use the official PHP image as the base image
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    nano \
    unzip \
    curl


# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set up the workbook
WORKDIR /var/www

# Copy the current project to the workbook
COPY . /var/www

# Install project dependencies
RUN composer install

RUN composer update

# Set the permissions for the Laravel storage folder
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Set the permissions for the Laravel public directory
RUN chown -R www-data:www-data /var/www/public

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|' /etc/apache2/sites-available/000-default.conf

# Set the start command
CMD ["apache2-foreground"]
