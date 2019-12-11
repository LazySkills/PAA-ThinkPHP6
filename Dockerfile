FROM php:7.4.0-fpm

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
        libzip-dev \
    && docker-php-ext-install \
       bcmath gd pdo_mysql \
       sockets zip sysvmsg sysvsem sysvshm \

# Install composer
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update --clean-backups \
# Clear dev deps
    && apt-get clean \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false

ADD . /app/paa_thinkphp6

RUN  cd /app/paa_thinkphp6 \
    && composer install \
    && composer clearcache

WORKDIR /app/paa_thinkphp6

EXPOSE 9000

CMD ["php", "/app/paa_thinkphp6/think", "run","-p 9000"]