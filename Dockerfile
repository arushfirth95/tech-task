# Use an official PHP image as a base image with Apache
FROM php:8.1-apache

# Install necessary dependencies
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        unzip \
        libonig-dev \
        libxml2-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev

# Enable necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath

# Enable Apache modules
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy the Laravel application code into the container
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies using Composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage/ /var/www/html/bootstrap/cache/ /var/www/html/public/

# Expose port 80
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
