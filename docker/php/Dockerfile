FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

# 必要なパッケージをインストール
RUN apt-get update \
    && apt-get install -y wget curl gnupg unzip \
    && apt-get install -y default-mysql-client zlib1g-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd

# Composer のインストール
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update

# Google Chrome のインストール
RUN wget -q -O /etc/apt/trusted.gpg.d/google-linux-signing-key.pub https://dl.google.com/linux/linux_signing_key.pub \
    && echo "deb [arch=amd64 signed-by=/etc/apt/trusted.gpg.d/google-linux-signing-key.pub] https://dl.google.com/linux/chrome/deb/ stable main" | tee /etc/apt/sources.list.d/google-chrome.list \
    && apt-get update \
    && apt-get install -y google-chrome-stable

# ChromeDriver をダウンロードしてインストール
RUN CHROME_VERSION=$(google-chrome --version | sed 's/Google Chrome \([0-9]*\)\..*/\1/') \
    && CHROMEDRIVER_VERSION=$(curl -s "https://chromedriver.storage.googleapis.com/LATEST_RELEASE_${CHROME_VERSION}") \
    && wget "https://chromedriver.storage.googleapis.com/113.0.5672.63/chromedriver_linux64.zip" \
    && unzip chromedriver_linux64.zip \
    && mv chromedriver /usr/local/bin/ \
    && chmod +x /usr/local/bin/chromedriver

WORKDIR /var/www