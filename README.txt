# DOCI

Project that runs the DOCI API (DAFL October Challenge Invitational) League
For the initial commit, this project only handles the initial roster creation and one supplemental addition for each season. It is not meant to be generalized for all fantasy baseball leagues.

To restore vendor directory:
composer update
C:\xampp\php784\php.ini needs to have these lines uncommented:
extension_dir = "ext"
extension=curl
extension=openssl
extension=pdo_mysql 

To run in debug:
php -S localhost:8000 -t public/