FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

# Configura MySQL para Cloud Run
RUN mkdir -p /var/run/mysqld \
    && chown -R mysql:mysql /var/run/mysqld \
    && usermod -d /var/lib/mysql mysql

# Instala PHP desde los repos oficiales de Ubuntu (versión estable)
RUN apt-get update && apt-get install -y --no-install-recommends \
    php \
    php-cli \
    php-common \
    php-mysql \
    php-mbstring \
    php-xml \
    php-zip \
    php-curl \
    php-bcmath \
    php-tokenizer \
    php-fileinfo \
    php-opcache \
    php-pdo \
    curl \
    git \
    unzip \
    ca-certificates \
    mysql-server mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Instala Node.js (LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html