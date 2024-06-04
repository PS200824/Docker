# Use the official PHP image as the base image
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev


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
#Ensure the 'public' directory is set as DocumentRoot
RUN sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf

#Enable Apache mod_rewrite
RUN a2enmod rewrite

#Set permissions for Laravel directories
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

#Ensure 'public' directory has correct permissions
RUN chown -R www-data:www-data /var/www/public
RUN chmod -R 755 /var/www/public

#Create SQLite database file and set permissions
RUN touch database/database.sqlite
RUN chown www-data:www-data database/database.sqlite
RUN chmod 664 database/database.sqlite

#Ensure the .env file has the correct database path
RUN sed -i 's|DB_DATABASE=.*|DB_DATABASE=/var/www/database/database.sqlite|' /var/www/.env

#Add user for Laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
RUN usermod -a -G www-data www

#Set permissions for Laravel application directory
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www

#Change current user to www-data (Apache user)
USER www-data

#Set ServerName to suppress Apache warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
EXPOSE 80
CMD ["apache2-foreground"]
