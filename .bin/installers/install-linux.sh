#!/bin/bash
# Install PHP, Composer, and NodeJS within a Linux environment.

if [ "$(whoami)" != "root" ]; then
    SUDO=sudo
fi

PHP_VERSION="8.1"
NODE_VERSION="18"

if ! command -v curl &> /dev/null
then
    ${SUDO} apt-get update
    ${SUDO} apt-get -y install curl
fi

INSTALL_NODE=false
if ! command -v node &> /dev/null
then
    INSTALL_NODE=true
else
    NODE_VERSION_INSTALLED=`node -v | cut -d "v" -f 2 | cut -d "." -f 1,2`
    if [ "$NODE_VERSION_INSTALLED" != "$NODE_VERSION" ]; then
        INSTALL_NODE=true
    fi
fi

if [ "$INSTALL_NODE" = true ]; then
    ${SUDO} apt-get update
    ${SUDO} apt-get install -y ca-certificates gnupg
    ${SUDO} mkdir -p /etc/apt/keyrings
    curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | ${SUDO} gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
    echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" | sudo tee /etc/apt/sources.list.d/nodesource.list
    ${SUDO} apt-get update
    ${SUDO} apt-get install nodejs -y
    echo "Latest version of NodeJS v${NODE_VERSION} installed."
fi

INSTALL_PHP=false
if ! command -v php &> /dev/null
then
    INSTALL_PHP=true
else
    PHP_VERSION_INSTALLED=`php -v | sed -n 1p | cut -d " " -f 2 | cut -d "-" -f 1 | cut -d "." -f 1,2`
    if [ "$PHP_VERSION_INSTALLED" != "$PHP_VERSION" ]; then
        INSTALL_PHP=true
    fi
fi

if [ "$INSTALL_PHP" = true ]; then
    ${SUDO} apt-get update
    ${SUDO} apt-get -y install lsb-release ca-certificates curl
    ${SUDO} curl -sSLo /usr/share/keyrings/deb.sury.org-php.gpg https://packages.sury.org/php/apt.gpg
    ${SUDO} sh -c 'echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
    ${SUDO} apt-get update
    ${SUDO} apt install "php${PHP_VERSION}" -y --no-install-recommends
    echo "PHP v${PHP_VERSION} installed."
fi

${SUDO} apt install "php${PHP_VERSION}-common" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-gd" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-ctype" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-curl" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-dom" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-fileinfo" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-mbstring" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-opcache" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-pdo" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-tokenizer" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-xml" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-zip" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-sqlite3" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-mysql" --no-install-recommends
${SUDO} apt install "php${PHP_VERSION}-bcmath" --no-install-recommends

INSTALL_COMPOSER=false
if ! command -v composer &> /dev/null
then
    INSTALL_COMPOSER=true
fi

if [ "$INSTALL_COMPOSER" = true ]; then
    ${SUDO} apt-get update
    ${SUDO} apt install composer --no-install-recommends
    echo "Composer installed"
fi

echo "Installation finished. You may now use node, php, and composer from your terminal. Examples: node -v, php -v, composer --version"
