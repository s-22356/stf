FROM php:8.0-apache

# Enable Apache mods
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html/STF_UDIN

# Install dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libonig-dev libxml2-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel project
COPY . /var/www/html/STF_UDIN


# Set permissions
RUN chown -R www-data:www-data /var/www/html/STF_UDIN \
    && chmod -R 755 /var/www/html/STF_UDIN

# Update Apache DocumentRoot to point to STF_UDIN/public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/STF_UDIN/public|' /etc/apache2/sites-available/000-default.conf

# Expose Apache port
EXPOSE 80
# Start Apache server
CMD ["apache2-foreground"]
