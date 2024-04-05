# Use an official PHP runtime as a parent image
FROM php:8.1

# Set the working directory to /app
WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the composer.json file and install dependencies
COPY /composer.json .
RUN composer install --no-scripts

# Copy the rest of the application source code
COPY . .

EXPOSE 8000

#CMD ["ls"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
