FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring

# Set working directory
WORKDIR /var/www/html

# Copy application source
COPY . /var/www/html

# Set permissions for web server user
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
