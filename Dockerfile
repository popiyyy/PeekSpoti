FROM php:8.3-apache

# install system dependencies 
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
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/* 

# Enable Apache mod_rewrite
RUN a2enmod rewrite 

# Install Composer 
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer 

# Set working directory 
WORKDIR /var/www/html

# Copy project files 
COPY . .

# Install PHP dependencies (including dev dependencies for local development)
RUN composer install --optimize-autoloader --no-interaction 

# Install Node dependencies and build frontend (keep node_modules for local development)
RUN npm ci && npm run build

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 

# Configure Apache to serve from /public 
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public 
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf 

# Add .htaccess support
RUN printf '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Expose port 80
EXPOSE 80

# Start script: clear caches, run migrations, and start Apache
# In local development, we want to clear configurations/route/view caches so that changes are instantly visible.
CMD php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    php artisan migrate --force && \
    apache2-foreground