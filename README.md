# projet-1-serv-o-back

Chaque groupe pourra cloner le repo back et lancer le serveur de dev pour avoir accés aux API

Commande pour démarrer le serveur de dev `php -S 0.0.0.0:8080 -t public`

## 1. Installation du projet:

### a. Installation des dépendances:

`composer create-project symfony/skeleton trocservice`

`mv trocservice/* trocservice/.* .`


`composer require symfony/webapp-pack` (installe toutes les dépendances de symfony)

### b. Création de la base de donnée:

Dans adminer on a créé la base de donnée "trocservice" avec l'utilisateur "trocservice / trocservice"

On créé la connexion a la bdd en modifiant le fichier .env et .env.local:

`DATABASE_URL="mysql://trocservice:trocservice@127.0.0.1:3306/trocservice?serverVersion=mariadb-10.3.25"`

Pour installer votre base de données en local, vous devez modifier votre fichier ".env.local" avec les infos suivantes en remplaçant les infos :

`DATABASE_URL="mysql://nomutilisateur:motdepasse@127.0.0.1:3306/nomdelabdd?serverVersion=mariadb-10.3.25"`

Ensuite, dans le terminal, vous devez faire un `bin/console d:d:c` (doctrine:database:create) 

On installe les entités via Symfony:

toutes les tables + relations du MLD -> cf. MLD cahier des charges![image](https://user-images.githubusercontent.com/108274050/205662589-38dd0b0e-f83e-4888-86bb-f0df3b9cdecf.png)
