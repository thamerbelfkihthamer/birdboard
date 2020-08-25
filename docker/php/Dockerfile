FROM php:7.2-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
    libmagickwand-dev --no-install-recommends \
    && docker-php-ext-install pdo_mysql bcmath


# Install composer
RUN echo "Install composer globally"
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


## NPM
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - \
&& apt-get install -q -y --no-install-recommends \
        nodejs \
        # to compile NPM dependencies
        automake autoconf build-essential \
        # pngquant libs
        libpng-dev \
        # mozjpeg libs (from imagemin-webpack-plugin)
        libtool nasm pkg-config

EXPOSE 9000
CMD ["php-fpm"]