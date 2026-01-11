FROM php:8.2-apache

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    curl \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Ativar mod_rewrite
RUN a2enmod rewrite

# Definir pasta de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . .

# Permissões
RUN chown -R www-data:www-data storage bootstrap/cache

# Instalar dependências
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Apache aponta para /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

EXPOSE 80
