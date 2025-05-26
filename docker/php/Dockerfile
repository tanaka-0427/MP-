# 使用イメージの指定
FROM php:7.4-fpm

# composer イメージ内から特定のファイルをコピー
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# コマンドの実行
# apt-getコマンドのアップデートと必要なライブラリやドライバのインストール
RUN apt-get update && apt-get install -y \
    git \
    zip unzip \
    curl \
    gnupg \
    && docker-php-ext-install pdo_mysql

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs