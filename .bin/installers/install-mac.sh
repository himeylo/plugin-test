#!/bin/bash
# Install Docker, Node Version Manager, NodeJS, and Visual Studio Code for Mac OS.
# You must have administrative rights on your computer to run this script.

if [ "$(whoami)" != "root" ]; then
    SUDO=sudo
fi

NVM_VERSION="0.39.5"
PHP_VERSION="8.1"
NODE_VERSION="18"

# Install NVM.
if ! command -v nvm &> /dev/null
then
    curl -o- "https://raw.githubusercontent.com/nvm-sh/nvm/v${NVM_VERSION}/install.sh" | bash
fi

# Install Node using NVM.
if ! command -v node &> /dev/null
then
    if ! command -v nvm &> /dev/null
    then
        echo "NVM is not yet available in your path. Please close your terminal and re-run this script so NodeJS can be installed using NVM."
        exit 1
    fi
    nvm install ${NODE_VERSION}
    nvm use ${NODE_VERSION}
    nvm alias default ${NODE_VERSION}
    echo "Node v${NODE_VERSION} installed."
fi

if ! command -v brew &> /dev/null
then
    curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh | ${SUDO} bash
    echo "Homebrew installed."
    if ! command -v brew &> /dev/null
    then
        # Add Homebrew to your PATH.
        HOMEBREW_PREFIX="/opt/homebrew"
        eval "\$(${HOMEBREW_PREFIX}/bin/brew shellenv)"
        echo "Homebrew added to your PATH."
        if ! command -v brew &> /dev/null
        then
            echo "Homebrew is not yet available in your path. Please close your terminal and re-run this script so applications can be installed using Homebrew."
            exit 1
        fi
    fi
fi

if ! command -v docker &> /dev/null
then
    brew install --cask docker
    echo "Docker installed."
fi

brew install --cask visual-studio-code

if ! command -v php &> /dev/null
then
    brew install php@${PHP_VERSION}
    echo "PHP v${PHP_VERSION} installed."
fi

if ! command -v composer &> /dev/null
then
    brew install composer
    echo "Composer installed"
fi

echo "Installation finished. You may now use node, nvm, php, composer, and docker from your terminal. Examples: node -v, nvm -v, php -v, composer -v, docker -v."
echo "Possible next steps:"
echo "- Add your laptop's SSH key to your GitHub account."
echo "- Log in to your Visual Studio Code profile to sync your settings."
