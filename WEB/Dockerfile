FROM php:8.3-fpm

# 1. Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    gnupg

# 2. Installer Node.js (pour Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# 3. Nettoyer le cache apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 4. Installer les extensions PHP
# On ajoute 'zip' et on configure 'intl' correctement
RUN docker-php-ext-configure intl && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip

# 5. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Définir le répertoire de travail
WORKDIR /var/www

# 7. Copier le projet
COPY . .

# 8. Installer les dépendances PHP et Node
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install

# 9. Forcer les permissions sur les binaires node_modules
RUN chmod -R +x node_modules/.bin/

# 10. Lancer le build de Vite
RUN npm run build

# 11. Fixer les permissions pour Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
