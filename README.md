# An API to access BileMo products catalog

## Installation
----------------

1) Clone this repository.

2) With a CLI, place you in the project folder and run "composer install" command.

3) In .env, choose your SGBD by remove the # before the DATABASE_URL required and complete asked informations. Then, type in the CLI the 2 following commands :
"php bin/console doctrine:database:create" 
"php bin/console doctrine:schema:create" 

4) To create tables in the database, type in CLI : 
"symfony console doctrine:migrations:migrate" 

5) Check fixtures directory to decide which fixtures you want in your database and define an account login and password for you (user 1 in fixtures/User.yaml), then type in CLI : "php bin/console hautelook:fixtures:load".



