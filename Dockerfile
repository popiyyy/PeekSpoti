FROM php:8.2-apache

# install system dependecies 
RUN apt-get update && apt-get install -y \
    git \
    curl \ 
    libpng-dev \ 
    libonig-dev \
    libxml2-dev \ 
    libpq-dev \ 
    libzip-dev \ 
    zip \ 
    unzip \ 
    nodejs \ 
    npm \ 
    && docker-php-ext-install pdo_pgsql mbstring eoxf pcntl bcmath gd zip \ 
    && apt-get clean && -rf /var/lib/apt/lists/* 

# Enable Apache mod_rewrite
RUN a]2enmod rewrite 

# Install Composerr 
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer 

# Set working directory 
WORKDIR /var/www/html

# Copy project files 
COPY . .

# Install PHP dependecies 
RUN composer install --no-dev --optimixe-autoloader --no-interaction 

# Install Node depedencies and build frontend 
RUN npm ci && npm run build && rm -rf node_modules

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 

# Configure Apache to serve from /public 
ENV APACHE_DOCUMENT_ROOT=/var/www/htm/public 
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \ 
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf 

# Add .htaccess support
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Expose port 80
EXPOSE 80

# Start script: run migrations + start Apache
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    apache2-foreground
