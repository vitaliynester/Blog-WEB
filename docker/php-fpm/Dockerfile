FROM php:7.4-fpm
RUN apt-get update && apt-get install -y zlib1g-dev libpq-dev git wget libicu-dev libxml2-dev libzip-dev vim \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-install zip xml

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

# Install Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www/blog

#CMD symfony serve --allow-http --no-tls --port=9000