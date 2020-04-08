#!/bin/bash
yum -y update
yum -y install vim
yum -y install httpd
yum -y install mod_ssl
yum -y install epel-release
yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm
yum-config-manager --enable remi-php72
yum -y update
yum -y install php
yum -y install php-common php-opcache php-mcrypt php-cli php-gd php-curl
yum -y install php-mysql
yum -y install git
yum -y install composer

# Sysadmin tools
# Managing processes
yum -y install htop

# Managing multiple ssh sessions
yum -y install tmux

yum -y isntall lsof

# PHP Configurations
# Create a backup of the php.ini file
sudo cp /etc/php.ini /etc/php.ini.bak

# Turn on display errors
sudo sed -i 's/display_errors = Off/display_errors = On/g' /etc/php.ini

# Allow php to write files
sudo sed -i 's/allow_url_fopen = Off/allow_url_fopen = On/g' /etc/php.ini

# Apache Configurations
sudo sed -i 's/AllowOverride None/AllowOverride All/g' /etc/httpd/conf/httpd.conf

# Start apache and enable it to run on startup
sudo systemctl start httpd
sudo systemctl enable httpd

# On instance create
# Turn off selinux
sudo setenforce 0

# Mount the file system
sudo mount -t nfs4 172.31.38.72:/ /var/www/html/

# Create a cronjob the remounts our file system on reboot
crontab -l | { cat; echo "@reboot sudo mount -t nfs4 172.31.38.72:/ /var/www/html/"; } | crontab -
