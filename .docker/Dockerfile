##################
# STAGE 0 : BASE #
##################
FROM php:8.2-fpm AS base

## Install dependencies
RUN apt update \
	&& apt install -y git rsync zip libicu-dev \
	# Install intl
	&& docker-php-ext-configure intl \
	&& docker-php-ext-install intl \
	# Install opcache
	&& docker-php-ext-install opcache

## Add the entrypoint script
ADD .docker/entrypoint.sh /entrypoint.sh

WORKDIR /var/www/les_editions_lunaires

# Copy App files
COPY composer.json composer.lock symfony.lock .env* ./
COPY bin bin/
COPY config config/
COPY public public/
COPY src src/

## Create empty var cache and var log directory
RUN mkdir -p /var/www/les_editions_lunaires/var/cache /var/www/les_editions_lunaires/var/log

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm", "-F"]

###################
# STAGE 1 : BUILD #
###################
FROM base as build

## Install composer 2
ADD ["https://getcomposer.org/installer", "https://composer.github.io/installer.sig", "./"]
RUN php ./installer --2 --install-dir="/usr/local/bin/" --filename="composer" && rm -f installer installer.sig
RUN mkdir -p /var/www/.composer/cache && chown -R www-data /var/www/.composer

CMD ["php-fpm", "-F"]

####################
### STAGE 2: Dev ###
####################

FROM build as dev

RUN apt update --allow-releaseinfo-change
RUN apt install -y --no-install-recommends gcc libc6-dev
RUN pecl install xdebug-beta && docker-php-ext-enable xdebug

# installing project
ENV APP_ENV=dev
ENV APP_ENV ${APP_ENV}
ENV OPCACHE_ENABLE=off

CMD ["php-fpm", "-F"]
