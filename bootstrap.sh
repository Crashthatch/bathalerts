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


sudo service apache2 restart;


