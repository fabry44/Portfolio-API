# Portfolio-API

## Table des matières
- [Présentation](#présentation)
- [Prérequis](#prérequis)
- [Installation](#installation)
    - [Cloner le projet](#cloner-le-projet)
    - [Configuration](#configuration)
    - [Génération des clés OAuth2](#génération-des-clés-oauth2)
    - [Lancement du projet](#lancement-du-projet)
- [Gestion des tokens OAuth2](#gestion-des-tokens-oauth2)
- [Commandes utiles](#commandes-utiles)
- [API Endpoints](#api-endpoints)
- [Docker](#docker)
- [Tests](#tests)
- [Licence](#licence)

## Présentation
Portfolio-API est une API construite avec Symfony 7 qui permet de gérer le backend d’un portfolio. Elle implémente OAuth2 pour l’authentification et est déployée via Docker.

## Prérequis
Avant d’installer le projet, assurez-vous d’avoir installé :
- Docker et Docker Compose
- PHP 8.3 ou supérieur
- Composer
- Symfony CLI
- MariaDB ou MySQL

## Installation

### Cloner le projet
```sh
git clone https://github.com/mon-compte/portfolio-api.git
cd portfolio-api
```

### Configuration
Copier le fichier `.env.example` en `.env` :
```sh
cp .env.example .env
```
Modifier les variables de configuration :
```ini
DATABASE_URL="mysql://root:@portfolio-db:3306/portfolio?serverVersion=mariadb-10.4.34&charset=utf8mb4"
OAUTH_PRIVATE_KEY=%kernel.project_dir%/config/jwt/private.pem
OAUTH_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
```

### Génération des clés OAuth2
```sh
mkdir -p config/jwt
openssl genpkey -algorithm RSA -out config/jwt/private.pem -aes256
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```
Ajouter la passphrase dans le fichier `.env` si nécessaire :
```ini
OAUTH_PASSPHRASE=ma_passphrase
```

### Lancement du projet
Démarrer les services Docker :
```sh
docker-compose up -d --build
```
Installer les dépendances :
```sh
docker exec -it portfolio-api composer install
```
Exécuter les migrations :
```sh
docker exec -it portfolio-api php bin/console doctrine:migrations:migrate
```
Créer un client OAuth2 :
```sh
docker exec -it portfolio-api php bin/console oauth2:create-client --redirect-uri=http://localhost
```

## Gestion des tokens OAuth2

### Obtenir un token d'accès
```sh
curl -X POST http://localhost:8000/token \
         -H "Content-Type: application/x-www-form-urlencoded" \
         -d "grant_type=client_credentials" \
         -d "client_id=TON_CLIENT_ID" \
         -d "client_secret=TON_SECRET" \
         -d "scope=portfolio"
```

### Accéder aux endpoints protégés
```sh
curl -H "Authorization: Bearer TON_ACCESS_TOKEN" http://localhost:8000/api/protected
```

## Commandes utiles

| Commande | Description |
|----------|-------------|
| `php bin/console cache:clear` | Nettoyer le cache Symfony |
| `php bin/console doctrine:migrations:diff` | Générer une nouvelle migration |
| `php bin/console doctrine:migrations:migrate` | Appliquer les migrations |
| `php bin/console oauth2:create-client` | Créer un client OAuth2 |
| `php bin/console security:hash-password` | Générer un hash de mot de passe |
| `php bin/console debug:container` | Lister les services Symfony |

## API Endpoints

### Endpoints protégés

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/protected` | Vérifier l'authentification avec OAuth2 |
| GET | `/api/portfolio` | Récupérer les projets du portfolio |

### Endpoints publics

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/token` | Obtenir un token OAuth2 |
| GET | `/health` | Vérifier si l'API est active |

## Docker

### Fichiers importants
- `docker-compose.yml` : Définition des services (API, base de données)
- `Dockerfile` : Image Docker pour Symfony

### Commandes Docker

| Commande | Description |
|----------|-------------|
| `docker-compose up -d --build` | Démarrer les conteneurs |
| `docker-compose down` | Arrêter les conteneurs |
| `docker ps` | Lister les conteneurs actifs |
| `docker logs portfolio-api` | Voir les logs |
| `docker exec -it portfolio-api bash` | Accéder au shell du conteneur |
| `docker-compose restart` | Redémarrer les services |

## Tests

### Lancer les tests PHPUnit
```sh
docker exec -it portfolio-api php bin/phpunit
```

### Tester l'authentification OAuth2
```sh
curl -X POST http://localhost:8000/token \
         -d "grant_type=client_credentials" \
         -d "client_id=TON_CLIENT_ID" \
         -d "client_secret=TON_SECRET" \
         -d "scope=portfolio"
```

## Licence
Ce projet est sous licence MIT.