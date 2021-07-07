FROM php:7.4-fpm
RUN apt -yqq update
RUN apt -yqq install libxml2-dev
RUN docker-php-ext-install pdo_mysql
RUN apt install cron -y

RUN apt-get install unzip

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it

WORKDIR /var/www

#COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN chown -R www-data:www-data ./*

RUN composer install

COPY ./docker-entrypoint.sh /

ENTRYPOINT ["/docker-entrypoint.sh"]

EXPOSE 9000
