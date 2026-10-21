FROM php:8.4-cli

RUN apt-get update && apt-get install -y --no-install-recommends libxml2-dev libonig-dev \
    && docker-php-ext-install dom mbstring \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
