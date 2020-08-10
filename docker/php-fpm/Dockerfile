FROM php:7.4-fpm

RUN groupadd -r app-user && useradd --no-log-init -r -g app-user app-user

RUN apt-get update && \
    apt-get -y install libpq-dev git && \
    docker-php-ext-install pdo pdo_pgsql pgsql bcmath

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin && \
        echo "alias composer='composer'" >> /root/.bashrc && \
        composer

WORKDIR /var/www

COPY app/composer.json app/composer.lock /var/www/

RUN chown -R app-user:app-user /var/www

RUN mkdir /home/app-user
RUN chown -R app-user:app-user /home/app-user

RUN composer install --prefer-source --no-interaction

USER app-user

CMD bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

CMD bin/console doctrine:fixtures:load

CMD ["php-fpm"]

EXPOSE 9000