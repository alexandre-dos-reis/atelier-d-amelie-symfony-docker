FROM php:7.4-apache

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        pcntl \
        gd \
        intl

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN apt update && apt install -yqq nodejs npm

COPY . /var/www/

COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN cd /var/www && \
    composer install && \
    npm install --force && \
    npm run build && \
    php bin/console assets:install

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www
USER www-data

ENTRYPOINT ["bash", "./docker/entrypoint.sh"]

EXPOSE 80