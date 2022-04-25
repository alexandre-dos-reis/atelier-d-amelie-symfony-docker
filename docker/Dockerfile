FROM php:7.4-apache

ARG APP_ENV
ARG APP_SECRET
ARG DATABASE_URL
ARG STRIPE_SECRET_KEY
ARG STRIPE_PUBLIC_KEY
ARG STRIPE_CONFIRM_END_POINT_KEY

ENV APP_ENV=${APP_ENV}
ENV APP_SECRET=${APP_SECRET}
ENV DATABASE_URL=$DATABASE_URL
ENV STRIPE_SECRET_KEY=$STRIPE_SECRET_KEY
ENV STRIPE_PUBLIC_KEY=$STRIPE_PUBLIC_KEY
ENV STRIPE_CONFIRM_END_POINT_KEY=$STRIPE_CONFIRM_END_POINT_KEY

# ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# RUN chmod +x /usr/local/bin/install-php-extensions && \
#     install-php-extensions \
#         pdo_mysql \
#         mbstring \
#         zip \
#         exif \
#         pcntl \
#         gd \
#         intl

# RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
#    mv composer.phar /usr/local/bin/composer

# RUN apt update && apt install -yqq nodejs npm

# COPY . /var/www/

# COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# RUN cd /var/www && \
#     composer install && \
#     npm install --force && \
#     npm run build && \
#     php bin/console assets:install

# WORKDIR /var/www

# RUN chown -R www-data:www-data /var/www
# USER www-data

# ENTRYPOINT ["bash", "./docker/entrypoint.sh"]

# EXPOSE 80