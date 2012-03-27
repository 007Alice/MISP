                                                                     

TODOs
-----

Auth
- Prevent bruteforce auth attempts

implement auditing/logging system
- add / edit events and signatures
- failed / success logins (with source IP, headers,...)

Security
- force cookie reset after login


INSTALLATION INSTRUCTIONS
-------------------------
Install the following libraries:
apt-get install zip
apt-get install pear
pear install Crypt_GPG    # need version >1.3.0 

Download CyDefSIG using git in the /var/www/ directory. 

cd /var/www/
git clone git@code.lab.modiss.be:cydefsig.git

Download and extract CakePHP 2.x to the web root directory:

cd /tmp/
wget https://nodeload.github.com/cakephp/cakephp/tarball/2.1
tar zxvf cakephp-cakephp-<version>.tar.gz
cd cakephp-cakephp-*

Now remove the app directory and move everything from CakePHP to var/www

rm -Rf app .gitignore 
mv * /var/www/cydefsig/
mv .??* /var/www/cydefsig/

Check if the permissions are set correctly using the following commands as root:

chown -R <user>:www-data /var/www/cydefsig
chmod -R 750 /var/www/cydefsig
chmod -R g+s /var/www/cydefsig
cd /var/www/cydefsig/app/
chmod -R g+w tmp
chmod -R g+w files

Import the empty MySQL database in /var/www/cydefsig/app/MYSQL.txt using phpmyadmin or mysql>.

Now configure your apache server with the DocumentRoot /var/www/cydefsig/app/webroot/

Configure the fields in the files:
database.php : login, port, password, database
bootstrap.php: CyDefSIG.*, GnuPG.*
core.php : debug, 

Generate a GPG encryption key.
sudo -u www-data gpg --homedir /var/www/cydefsig/.gnupg --gen-key


Now log in using the webinterface:
The default user/pass = admin@admin.test/admin 

Don't forget to change the email, password and authentication key after installation.



Recommended patches
-------------------
By default CakePHP exposes his name and version in email headers. Apply a patch to remove this behavior.