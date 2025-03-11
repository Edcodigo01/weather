# Set the base image for subsequent instructions
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    unzip \
    git \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpq-dev \
    cron \
    supervisor \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Remove default server definition
RUN rm -rf /var/www/html

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Create supervisor configuration directory
RUN mkdir -p /etc/supervisor/conf.d

# Copy the supervisor configuration file
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Ensure log files are created
RUN touch /var/log/supervisor.log

# Set permissions for log files
RUN chmod -R 777 /var/log

# user root
USER root

# Start supervisor to manage processes
CMD ["/usr/bin/supervisord"]
