FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Enable Apache mod_rewrite (optional)
RUN a2enmod rewrite

# Restart Apache inside the container
CMD ["apache2-foreground"]