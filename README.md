# An API to access BileMo products catalog

## Installation

---

1. Clone this repository.

2. With a CLI, place you in the project folder and run "composer install" command.

3. In .env, choose your SGBD by remove the # before the DATABASE_URL required and complete asked informations. Then, type in the CLI the 2 following commands :
   "php bin/console doctrine:database:create"
   "php bin/console doctrine:schema:create"

4. To create tables in the database, type in CLI :
   "symfony console doctrine:migrations:migrate"

5. Check fixtures directory to decide which fixtures you want in your database and define an account login and password for you (in fixtures/User.yaml > user1).
   Don't forget to hash your password before type it in the fixtures. Use "symfony console security:hash-password" command to obtain it. If the hashed password contains "\$" character, you should escape it.
   Then type in CLI : "php bin/console hautelook:fixtures:load".

6. Generate your key pair for JWT Token by typing in your CLI :
   "php bin/console lexik:jwt:generate-keypair"
