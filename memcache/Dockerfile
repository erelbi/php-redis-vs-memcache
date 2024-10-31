FROM php:8.2-rc-fpm


RUN apt-get update && apt-get install -y \
    libmemcached-dev \
    zlib1g-dev \
    pkg-config \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*


RUN yes '' | pecl install -f memcached-3.2.0 \
  && docker-php-ext-enable memcached


RUN mkdir -p /usr/local/etc/php/conf.d/
RUN echo "extension=memcached.so" > /usr/local/etc/php/conf.d/memcached.ini
RUN echo "session.save_handler=memcached" >> /usr/local/etc/php/conf.d/memcached.ini
RUN echo "session.save_path='memcached:11211'" >> /usr/local/etc/php/conf.d/memcached.ini

WORKDIR /var/www/html

COPY index.php /var/www/html/

RUN chown -R www-data:www-data /var/www/html