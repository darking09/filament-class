FROM php:8.3.3-fpm-alpine AS php-system-setup

# Install system dependencies
RUN apk add --no-cache dcron busybox-suid libcap curl zip unzip git nano supervisor mysql-client

# Install PHP extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions intl bcmath gd pdo_mysql pdo_pgsql opcache redis uuid exif pcntl zip pdo opcache phar iconv curl openssl mbstring tokenizer fileinfo json xml xmlwriter simplexml dom pdo_sqlite tokenizer

# Install supervisord implementation
#COPY --from=ochinchina/supervisord:latest /usr/local/bin/supervisord /usr/local/bin/supervisord

# Install caddy
COPY --from=caddy:2.2.1 /usr/bin/caddy /usr/local/bin/caddy
RUN setcap 'cap_net_bind_service=+ep' /usr/local/bin/caddy

# Install composer
COPY --from=composer/composer:2 /usr/bin/composer /usr/local/bin/composer

FROM php-system-setup AS app-setup

# Set working directory
ENV LARAVEL_PATH=/srv/app
WORKDIR $LARAVEL_PATH

# Add non-root user: 'app'
ARG NON_ROOT_GROUP=${NON_ROOT_GROUP:-app}
ARG NON_ROOT_USER=${NON_ROOT_USER:-app}
RUN addgroup -S $NON_ROOT_GROUP && adduser -S $NON_ROOT_USER -G $NON_ROOT_GROUP
RUN addgroup $NON_ROOT_USER wheel

# Set cron job
COPY ./deploy/config/crontab /etc/crontabs/$NON_ROOT_USER
RUN chmod 777 /usr/sbin/crond
RUN chown -R $NON_ROOT_USER:$NON_ROOT_GROUP /etc/crontabs/$NON_ROOT_USER && setcap cap_setgid=ep /usr/sbin/crond

# Switch to non-root 'app' user & install app dependencies
COPY composer.json composer.lock ./
RUN chown -R $NON_ROOT_USER:$NON_ROOT_GROUP $LARAVEL_PATH
USER $NON_ROOT_USER
RUN rm -rf /home/$NON_ROOT_USER/.composer

# Install composer dependencies
RUN composer install --no-scripts --no-autoloader --no-dev --no-interaction --no-progress --no-suggest

# Copy app
COPY --chown=$NON_ROOT_USER:$NON_ROOT_GROUP . $LARAVEL_PATH/
COPY ./deploy/config/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Start app
EXPOSE 80
COPY --chown=$NON_ROOT_USER:$NON_ROOT_GROUP ./entrypoint.sh $LARAVEL_PATH/entrypoint.sh
RUN chmod 777 $LARAVEL_PATH/entrypoint.sh

#CMD ["./entrypoint.sh"]
CMD /bin/sh -i $LARAVEL_PATH/entrypoint.sh;
