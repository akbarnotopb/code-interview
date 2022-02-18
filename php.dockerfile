FROM php:7.4-fpm-alpine

RUN apk add --no-cache libpng libpng-dev libzip-dev

RUN docker-php-ext-install pdo pdo_mysql gd


ARG PHPGROUP
ARG PHPUSER

ENV PHPGROUP=${PHPGROUP}
ENV PHPUSER=${PHPUSER}

RUN addgroup --system ${PHPGROUP}; exit 0
RUN adduser --system -G ${PHPGROUP} -s /bin/sh -D ${PHPUSER}; exit 0

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chown -R www-data:www-data /var/www/html

RUN apk add git

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
