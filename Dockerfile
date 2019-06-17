# API deployment image
# to use this:
# to build:
#   docker build -t xtract_api_v1_000 .
# to run:
#   docker run -d -v /tmp:/etc/apache2/ssl:ro --name api -e VIRTUAL_HOST=localhost -e VIRTUAL_PORT=443 -e VIRTUAL_PROTO=https xtract_api_v1_000

# start with official php7 apache base image
FROM php:7.1-apache-jessie

# default values for env vars
ENV APP_NAME=XIS
ENV APP_ENV=local
ENV APP_DEBUG=true
ENV APP_LOG_LEVEL=debug
ENV APP_URL=https://localhost

ENV DB_CONNECTION=mysql
ENV DB_HOST=localhost
ENV DB_PORT=3306

ENV APP_SERVER_URL=https://localhost
ENV APP_PATH=/api
ENV API_LOG_ENABLED=true
ENV OAUTH_ENABLED=true
ENV CORS_ALLOWED_ORIGINS = https://localhost

# setup ssl and rewrite engine
RUN a2enmod ssl
RUN a2enmod rewrite

# install php mysql support
RUN docker-php-ext-install pdo pdo_mysql

COPY ./docker/000-default-ssl.conf /etc/apache2/sites-enabled/000-default-ssl.conf

# install dependences to get composer
RUN apt-get update -y && apt-get install -y openssl zip unzip git curl

# download composer and install it to /usr/local/bin
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# copy project from one directory up to image apache web directory
COPY . /var/www/html/api

# delete the docker directory that went along for the ride
RUN rm -r /var/www/html/api/docker

# move to api directory in the image
WORKDIR /var/www/html/api

# fix permissions for swagger and logs
RUN chgrp -R www-data storage bootstrap/cache app/swagger
RUN chmod -R ug+rwx storage bootstrap/cache app/swagger

# Run install in the image to load node dependencies
RUN composer install

# expose the port to serve the API on
EXPOSE 443
