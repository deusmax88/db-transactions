FROM php:8.0

RUN pecl install xdebug-3.0.3
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-enable xdebug