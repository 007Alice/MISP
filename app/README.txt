                                                                     
                                                                     
                                                                     
                                             
TODOs
-----

Auth
- Use captcha authentication
- cleanup ACL and do it using the CakePHP concept
- password strength requirements

implement auditing/logging system
- add / edit events and signatures
- failed / success logins (with source IP, headers,...)

Security
- apply CSRF checks on the delete parameters by enabling security modules and rewriting some parts
- force cookie reset after login


INSTALLATION INSTRUCTIONS
-------------------------
Download CyDefSIG using git in the /var/www/ directory. 

cd /var/www/
git clone git@code.lab.modiss.be:cydefsig.git

Download and extract CakePHP 1.3 to the web root directory:

cd /tmp/
wget https://nodeload.github.com/cakephp/cakephp/tarball/1.3
tar zxvf cakephp-cakephp-<version>.tar.gz
cd cakephp-cakephp-*

Now remove the app directory and move everything from CakePHP to var/www

rm -Rf app
mv * /var/www/cydefsig/
mv .??* /var/www/cydefsig/

Create the 'tmp' directory with the necessary sub directories:

mkdir /var/www/cydefsig/app/tmp
mkdir /var/www/cydefsig/app/tmp/sessions
mkdir /var/www/cydefsig/app/tmp/logs
mkdir /var/www/cydefsig/app/tmp/cache

Check if the permissions are set correctly using the following commands as root:

chown -R <user>:www-data /var/www/cydefsig
chmod -R 750 /var/www/cydefsig
chmod -R g+s /var/www/cydefsig
cd /var/www/cydefsig/app/
chmod -R g+w tmp

Import the empty MySQL database in /var/www/cydefsig/app/MYSQL.txt using phpmyadmin or mysql>.

Now configure your apache server with the DocumentRoot /var/www/cydefsig/app/webroot/

The default user/pass = admin@admin.com/admin 
Don't forget to change the email, password and authentication key after installation.

Recommended patches
-------------------
By default CakePHP exposes his name and version in email headers. Apply a patch to remove this behavior.