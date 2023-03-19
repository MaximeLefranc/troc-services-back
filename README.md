# Project Troc'Services Back

Command to start the development server `php -S 0.0.0.0:8080 -t public`

## 1. Installing the project:

### a. Installing the dependencies:

`composer create-project symfony/skeleton trocservice`

`mv trocservice/* trocservice/.* .`


`composer require symfony/webapp-pack:*` (install all symfony dependencies)

### b. Creation of the database:

In adminer or php my admin we created the database "trocservice" with the user "trocservice / trocservice"

We create the connection to the database by modifying the .env and .env.local files:

`DATABASE_URL="mysql://trocservice:trocservice@127.0.0.1:3306/trocservice?serverVersion=mariadb-10.3.25"`

To install your database locally, you must modify your ".env.local" file with the following info by replacing the info:

`DATABASE_URL="mysql://nomutilisateur:motdepasse@127.0.0.1:3306/nomdelabdd?serverVersion=mariadb-10.3.25"`

Then in the terminal you need to do  `bin/console d:d:c` (doctrine:database:create)

We install the entities via Symfony:

all the tables + relations

We create the entities via Doctrine with ` bin\console make:entity`

We add the properties of each entity and we migrate the data so that they appear in the database

Migration:

`bin/console make:migration`

`bin/console doctrine:migrations:migrate `

We create the relationships between the entities (between the tables) with make:entity and the type "relation"
-> see MLD and Data Dictionary.
