FROM php:7.4.0-fpm

# Install PHP oniguruma
RUN git clone https://github.com/kkos/oniguruma.git \
    cd oniguruma \
    autoreconf -vfi \
    ./configure \
    make \
    make install\


# 设置国内源
RUN mv /etc/apt/sources.list /etc/apt/sources.list.back && \
     echo '# 默认注释了源码镜像以提高 apt update 速度，如有需要可自行取消注释 \n \
     deb https://mirrors.tuna.tsinghua.edu.cn/debian buster main' >> /etc/apt/sources.list
# Libs -y --no-install-recommends
RUN apt-get update \
    && apt-get install -y \
        curl wget git zip unzip less vim procps lsof tcpdump htop openssl \
        libz-dev \
        libssl-dev \
        libnghttp2-dev \
        libpcre3-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \

# Install PHP extensions
    && docker-php-ext-install \
       bcmath gd pdo_mysql mbstring sockets zip sysvmsg sysvsem sysvshm \

# Install composer
     curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update --clean-backups \
# Clear dev deps
    && apt-get clean \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
# Timezone
    && cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    && echo "[Date]\ndate.timezone=${TIMEZONE}" > /usr/local/etc/php/conf.d/timezone.ini

RUN  cd /app/paa_thinkphp6 \
    && composer install \
    && composer clearcache

WORKDIR /app/paa_thinkphp6
