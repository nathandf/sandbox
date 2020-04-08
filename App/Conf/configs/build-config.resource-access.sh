#!/bin/bash
# <Production webserver configurations>
yum -y update
yum -y install httpd
yum -y install epel-release
yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm
yum-config-manager --enable remi-php72
yum -y update
yum -y install php
yum -y install php-common php-opcache php-mcrypt php-cli php-gd php-curl
yum -y install php-mysql
yum -y install vim
yum -y install git
yum -y install composer
yum -y install mod_ssl

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

# Mount file system above document root
sudo mount -t nfs4 172.31.38.72:/ /var/www/efs/

# </Production webserver configurations>
# <Resource access server configurations>

# Install phpMyAdmin
yum -y install phpmyadmin

# Replace the host string with the host string set the AWS RDS endpoint
# Set the public dns for the AWS RDS instance
aws_rds_public_dns="panicpal-production.cjg3laxqedy3.us-east-2.rds.amazonaws.com"

# Create the string that will replace the orig host config str with the host config string
# set equal to the aws rds public dns
host_config_str_replacement="\$cfg['Servers'][\$i]['host'] = '$aws_rds_public_dns';"

# Get the current string that will be replaced in the phpMyAdmin config file
# -P "PERL regex"
# -o "only matching"
host_config_str=$(grep -Po '\$cfg\[\047Servers\047\]\[\$i\]\[\047host\047\][\s]*=[\s]*\047[\S\s]*\047;^[\\n]' /etc/phpMyAdmin/config.inc.php)


# Replace the original host string with the replacement host string
# with the rds instance's public dns after the equal sign
sudo sed -i "s|$host_config_str|$host_config_str_replacement|g" /etc/phpMyAdmin/config.inc.php

# Change the host of phpMyAdmin in the phpMyAdmin configs
# vim /etc/phpMyAdmin/config.inc.php
# Change the line below...
# cfg['Servers'][$i]['host'] = 'localhost'
# to...
# cfg['Servers'][$i]['host'] = '<Your AWS RDS endpoint goes here>'

# </Resource access server configurations>
