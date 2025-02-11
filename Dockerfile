# Étape 2 : Image finale avec PHP-Apache
FROM php:8.3-apache

# Mise à jour et installation des extensions nécessaires
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Installer un gestionnaire d'extensions PHP
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Installer les extensions PHP nécessaires
RUN install-php-extensions \
    gd \
    xdebug \
    pdo_mysql \
    zip \
    intl \
    ctype \
    iconv \
    xml \
    curl \
    json \
    mbstring \
    sodium 

# Installer un gestionnaire d'extensions PHP
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Activer le module rewrite (pour Symfony)
RUN a2enmod rewrite

# (Optionnel) a2enmod headers si besoin de headers particuliers
# RUN a2enmod headers

# Copie de la configuration Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

# Installation de Composer
WORKDIR /var/www/html
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Copie du script de démarrage
COPY docker.sh /usr/local/bin/docker.sh
RUN chmod +x /usr/local/bin/docker.sh

# Expose le port 80
EXPOSE 80

# Commande d'entrée
ENTRYPOINT ["/usr/local/bin/docker.sh"]
