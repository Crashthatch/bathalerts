#!/bin/bash
set -x; #echo on

#Ideally would use Ansible to do this stuff instead of a shell script. But the Ansible Controller-server can't be windows, so direct provisioning using vagrant doesn't work.
# Possible workaround here boots VM then runs ansible locally to complete the rest of the setup: https://groups.google.com/forum/#!topic/vagrant-up/3fNhoow7mTE

apt-get update;
apt-get install -q -y git apache2 php5 curl php5-curl git;

echo '<VirtualHost *:80>
             #ServerName www.example.com

             ServerAdmin webmaster@localhost
             DocumentRoot /vagrant/

             ErrorLog ${APACHE_LOG_DIR}/error.log
             CustomLog ${APACHE_LOG_DIR}/access.log combined

             <Directory /vagrant/>
                 Options Indexes FollowSymLinks
                 AllowOverride None
                 Require all granted
             </Directory>
     </VirtualHost>' | sudo tee /etc/apache2/sites-available/001-bathalerts.conf

sudo a2dissite 000-default
sudo a2ensite 001-bathalerts

echo '
display_errors = On
' >> /etc/php5/apache2/php.ini

sudo service apache2 restart;


sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password password 3QDr2mrhYVEjnp'
sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password_again password 3QDr2mrhYVEjnp'

sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/dbconfig-install boolean false'
sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2'

sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/app-password-confirm password 3QDr2mrhYVEjnp'
sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/admin-pass password 3QDr2mrhYVEjnp'
sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/password-confirm password 3QDr2mrhYVEjnp'
sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/setup-password password 3QDr2mrhYVEjnp'
sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/database-type select mysql'
sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/app-pass password 3QDr2mrhYVEjnp'

sudo debconf-set-selections <<< 'dbconfig-common dbconfig-common/mysql/app-pass password 3QDr2mrhYVEjnp'
sudo debconf-set-selections <<< 'dbconfig-common dbconfig-common/mysql/app-pass password'
sudo debconf-set-selections <<< 'dbconfig-common dbconfig-common/password-confirm password 3QDr2mrhYVEjnp'
sudo debconf-set-selections <<< 'dbconfig-common dbconfig-common/app-password-confirm password 3QDr2mrhYVEjnp'
sudo debconf-set-selections <<< 'dbconfig-common dbconfig-common/app-password-confirm password 3QDr2mrhYVEjnp'
sudo debconf-set-selections <<< 'dbconfig-common dbconfig-common/password-confirm password 3QDr2mrhYVEjnp'

sudo apt-get install -y -q mysql-server;

mysql -u root -p3QDr2mrhYVEjnp -e "CREATE USER 'bathalert_user'@'localhost' IDENTIFIED BY 'LAp7kWEaQv';"
mysql -u root -p3QDr2mrhYVEjnp -e "GRANT ALL ON bathalerts.* TO 'bathalert_user'@'localhost';"

mysql -u bathalert_user -pLAp7kWEaQv < /vagrant/Schema/db.sql

sudo apt-get install php5-mysqlnd
sudo service apache2 restart;