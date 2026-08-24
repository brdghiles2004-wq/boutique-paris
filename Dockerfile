FROM php:8.2-cli

# System dependencies

RUN apt-get update && apt-get install -y 
git 
unzip 
curl 
libzip-dev 
libpng-dev 
libonig-dev 
libxml2-dev 
libpq-dev 
nodejs 
npm 
&& docker-php-ext-install 
pdo_mysql 
pdo_pgsql 
mbstring 
bcmath 
exif 
pcntl 
zip 
&& apt-get clean 
&& rm -rf /var/lib/apt/lists/*

# Composer

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project

COPY . .

# Create temporary SQLite database

RUN mkdir -p database 
&& touch database/database.sqlite

# Install PHP dependencies

RUN composer install 
--no-dev 
--optimize-autoloader 
--no-interaction 
--no-scripts

# Install frontend dependencies

RUN npm install

# Build Vite

RUN npm run build

# Laravel permissions

RUN mkdir -p 
storage/framework/cache 
storage/framework/sessions 
storage/framework/views 
storage/logs 
bootstrap/cache 
&& chmod -R 775 storage bootstrap/cache

# Render port

EXPOSE 8080

# Start Laravel

CMD ["sh", "-c", "echo '=== STARTING BOUTIQUE PARIS ==='; echo "PORT=$PORT"; echo "DB_CONNECTION=$DB_CONNECTION"; echo '=== PHP VERSION ==='; php -v; echo '=== ARTISAN ==='; php artisan --version; echo '=== CONFIG CLEAR ==='; php artisan config:clear; echo '=== ROUTE CLEAR ==='; php artisan route:clear; echo '=== VIEW CLEAR ==='; php artisan view:clear; echo '=== MIGRATION ==='; php artisan migrate --force; echo '=== START SERVER ==='; php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
