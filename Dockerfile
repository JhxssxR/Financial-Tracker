# Use official PHP with Apache

FROM php:8.2-apache



# Install required PHP extensions for Laravel

RUN apt-get update && apt-get install -y \

  git unzip libpq-dev libzip-dev zip \

  && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip



# Enable Apache mod_rewrite (needed for Laravel routes)

RUN a2enmod rewrite



# Set Apache DocumentRoot to /var/www/html/public (Laravel entry point)

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \

 && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf



# Copy app code

COPY . /var/www/html/



# Create uploads folder and set permissions

RUN mkdir -p /var/www/html/public/uploads \

  && chown -R www-data:www-data /var/www/html/public/uploads \

  && chmod -R 775 /var/www/html/public/uploads


# Set working dir

WORKDIR /var/www/html

# Install Composer //changes

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies

RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel storage and cache

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose Render's required port

EXPOSE 10000

# Start Apache

CMD ["apache2-foreground"]