FROM php:8.0-apache

# Set environment variables for non-interactive installs and default user
ENV DEBIAN_FRONTEND=noninteractive
ARG APACHE_USER=www-data
ARG APACHE_GROUP=www-data

# Enable Apache mods
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html/STF_UDIN

# Install system dependencies required for PHP extensions and Composer
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libjpeg-dev \
    libfreetype6-dev \
    # Clean up apt caches to keep image size down
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath \
    gd

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy only the composer files first to leverage Docker layer caching.
# If composer.json/lock don't change, these steps won't rerun.
COPY composer.json composer.lock ./

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy the rest of the Laravel project files AFTER composer install
COPY . .

# --- IMPORTANT: ADDED/MODIFIED PERMISSION SETTINGS ---
# Set appropriate ownership and permissions for Laravel's storage and bootstrap/cache.
# These directories need to be writable by the web server user (www-data).
RUN chown -R ${APACHE_USER}:${APACHE_GROUP} storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    # Set more restrictive permissions for other Laravel files/directories for security.
    # This prevents the web server from writing to files it shouldn't.
    && chmod -R 755 . \
    # Ensure group write access for storage/bootstrap/cache explicitly if 775 is not sufficient
    # for some specific host/container UID/GID mappings, though 775 should cover it.
    && chmod -R ug+rwx storage bootstrap/cache
# --- END PERMISSION MODIFICATION ---

# Update Apache DocumentRoot to point to STF_UDIN/public
# Also ensure AllowOverride All for .htaccess to work
RUN sed -i "s|DocumentRoot /var/www/html|DocumentRoot /var/www/html/STF_UDIN/public|" /etc/apache2/sites-available/000-default.conf \
    && sed -i "s|<Directory /var/www/>|<Directory /var/www/html/STF_UDIN/public/>|" /etc/apache2/sites-available/000-default.conf \
    && sed -i "s|AllowOverride None|AllowOverride All|" /etc/apache2/sites-available/000-default.conf

# Expose Apache port
EXPOSE 80
# Start Apache server
CMD ["apache2-foreground"]
