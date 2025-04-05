# Usa imagem oficial do PHP com Apache
FROM php:8.1-apache

# Instala extensões e dependências necessárias
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    nano \
    libzip-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libpq-dev \
    libmcrypt-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia os arquivos do projeto
COPY . /var/www/html

# Define diretório de trabalho
WORKDIR /var/www/html

# Permissões e configurações do Apache
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Expõe a porta padrão do Apache
EXPOSE 80
