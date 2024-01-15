#!/usr/bin/env bash

source /app/env/vagrant-provision/common.sh
PHP_VERSION=8.3

#== Import script args ==

timezone=$(echo "$1")

#== Provision script ==

echo "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

echo "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password
echo "Done!"

echo "Update OS software"
echo "set grub-pc/install_devices /dev/sda" | debconf-communicate
apt-get update
apt-get upgrade -y

apt-get install -y debconf-utils lsb-release ca-certificates apt-transport-https software-properties-common curl gnupg
echo "Done!"

echo "PHP SURY Repo"
curl -sSLo /usr/share/keyrings/deb.sury.org-php.gpg https://packages.sury.org/php/apt.gpg
echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
echo "Done!"

echo "Updating new repos"
apt-get update
echo "Done!"

echo "Install additional software"
apt-get install -y vim php${PHP_VERSION}-curl php${PHP_VERSION}-cli php${PHP_VERSION}-intl php${PHP_VERSION}-gd \
php${PHP_VERSION}-fpm php${PHP_VERSION}-mbstring php${PHP_VERSION}-xml php${PHP_VERSION}-zip php${PHP_VERSION}-xdebug \
php${PHP_VERSION}-apcu php${PHP_VERSION}-pgsql php${PHP_VERSION}-soap unzip nginx nodejs npm php-pcov php${PHP_VERSION}-tidy
echo "Done!"

echo "Configure PHP and PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf
sed -i 's/display_errors = Off/display_errors = On/g' /etc/php/${PHP_VERSION}/fpm/php.ini
sed -i 's/memory_limit = 128M/memory_limit = 512M/g' /etc/php/${PHP_VERSION}/fpm/php.ini
sed -i 's/post_max_size = 8M/post_max_size = 256M/g' /etc/php/${PHP_VERSION}/fpm/php.ini
sed -i 's/;max_input_vars = 1000/max_input_vars = 7500/g' /etc/php/${PHP_VERSION}/fpm/php.ini
sed -i 's/;date.timezone = /date.timezone = "Europe/Lisbon"/g' /etc/php/${PHP_VERSION}/fpm/php.ini
ln -s /app/env/php/fpm-php.ini /etc/php/${PHP_VERSION}/fpm/php.ini
echo "Done!"

echo "Configure xdebug"
rm /etc/php/${PHP_VERSION}/mods-available/xdebug.ini
ln -s /app/env/php/xdebug.ini /etc/php/${PHP_VERSION}/mods-available/xdebug.ini
echo "Done!"

echo "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

echo "Enabling site configuration"
ln -s /app/env/nginx/app.conf /etc/nginx/sites-enabled/app.conf
rm /etc/nginx/sites-enabled/default
echo "Done!"

curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

cd /app/src/bin/
wget https://github.com/fabpot/local-php-security-checker/releases/download/v2.0.3/local-php-security-checker_2.0.3_linux_amd64
mv local-php-security-checker_2.0.3_linux_amd64 local-php-security-checker
chmod +x local-php-security-checker
chown vagrant:vagrant local-php-security-checker
echo "Done!"
