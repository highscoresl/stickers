FROM dunglas/frankenphp

RUN apt-get update \
    && apt-get install -y git acl wget zip libzip-dev libpng-dev zlib1g-dev libzip-dev libxml2-dev libicu-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip gd soap

RUN apt-get install -y nodejs npm \
    && npm install -g yarn

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer
RUN composer self-update

RUN mkdir /code

WORKDIR /code
