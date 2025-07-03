FROM php:8.3

ARG UID
ARG GID

ENV UID=${UID:-1000}
ENV GID=${GID:-1000}

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl gnupg2 zip libzip-dev libpng-dev libonig-dev libxml2-dev \
    libgtk-3-0 libnss3 libxss1 libasound2 libx11-xcb1 libxcomposite1 libxdamage1 libxrandr2 \
    libgbm1 libpango-1.0-0 libpangocairo-1.0-0 libatk-bridge2.0-0 libatk1.0-0 libcups2 \
    # GTK dependencies
    libcanberra-gtk-module libcanberra-gtk3-module \
    # OpenGL dependencies
    libgl1 libgl1-mesa-glx libegl1 libgles2 \
    # Mesa drivers
    mesa-utils \
    mesa-va-drivers \
    mesa-vdpau-drivers \
    mesa-vulkan-drivers \
    libgl1-mesa-dri \
    libsodium-dev \
    && docker-php-ext-install pdo mbstring zip exif pcntl sodium bcmath

# Install Node.js 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2.7.2 /usr/bin/composer /usr/bin/composer

RUN groupadd -g ${GID} dockeruser
RUN useradd -m -u ${UID} -g dockeruser -s /bin/bash dockeruser

USER dockeruser

CMD [ "composer", "native:dev" ]
