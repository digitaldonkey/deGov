#
# DeGov is based on a docker-library maintained by the Docker Community.
#
# @see https://github.com/docker-library/drupal
#
# Readme

#
# It is a "Official Image" maintained by
# @see https://docs.docker.com/docker-hub/official_images/
#
# We use circleci/php:apache-stretch image and add config from docker-library/drupal
# (https://github.com/docker-library/docs/tree/master/drupal)

FROM circleci/php:7.3-apache-stretch
# which is based on php:7.3.3-apache-stretch
# @readme https://hub.docker.com/_/php
# @see https://github.com/CircleCI-Public/circleci-dockerfiles/blob/master/php/images/7.3.3-apache-stretch/Dockerfile

USER root

# Drupal Recommended Config from
# https://github.com/docker-library/drupal/blob/6a99936a0870ae9d95d9e6070d1ffe7eb0e1f406/8.7/apache/Dockerfile
# install the PHP extensions we need
RUN set -eux; \
	\
	if command -v a2enmod; then \
		a2enmod rewrite; \
	fi; \
	\
	savedAptMark="$(apt-mark showmanual)"; \
	\
	apt-get update; \
	apt-get install -y --no-install-recommends \
		libfreetype6-dev \
		libjpeg-dev \
		libpng-dev \
		libpq-dev \
		libzip-dev \
		mysql-client \
	; \
	\
	docker-php-ext-configure gd \
		--with-freetype-dir=/usr \
		--with-jpeg-dir=/usr \
		--with-png-dir=/usr \
	; \
	\
	docker-php-ext-install -j "$(nproc)" \
		gd \
		opcache \
		pdo_mysql \
		pdo_pgsql \
		zip \
	; \
	\
# reset apt-mark's "manual" list so that "purge --auto-remove" will remove all build dependencies
	apt-mark auto '.*' > /dev/null; \
	apt-mark manual $savedAptMark; \
	ldd "$(php -r 'echo ini_get("extension_dir");')"/*.so \
		| awk '/=>/ { print $3 }' \
		| sort -u \
		| xargs -r dpkg-query -S \
		| cut -d: -f1 \
		| sort -u \
		| xargs -rt apt-mark manual; \
	\
	apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
	rm -rf /var/lib/apt/lists/*

# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=60'; \
		echo 'opcache.fast_shutdown=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Install git-lfs
RUN curl -s https://packagecloud.io/install/repositories/github/git-lfs/script.deb.sh | sudo bash && apt-get install git-lfs

COPY vhost.conf /etc/apache2/sites-available/project.conf

USER circleci

ENV PATH /home/circleci/project/bin:/home/circleci/.local/bin:/home/circleci/bin:${PATH}
