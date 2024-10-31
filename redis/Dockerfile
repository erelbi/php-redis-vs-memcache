FROM php:8.2-rc-fpm


RUN apt-get update && apt-get install -y \
    libzstd-dev \
    pkg-config \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*


RUN pecl install redis \
    && docker-php-ext-enable redis


RUN mkdir -p /usr/local/etc/php/conf.d/
RUN echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini
RUN echo "session.save_handler=redis" >> /usr/local/etc/php/conf.d/redis.ini
RUN echo "session.save_path='tcp://redis:6379'" >> /usr/local/etc/php/conf.d/redis.ini

WORKDIR /var/www/html

COPY index.php /var/www/html/

RUN chown -R www-data:www-data /var/www/html