Portfolio-API (Backend)

📂 Projet Full-Stack : API Symfony (backend) & Astro (frontend)

🔗 Backend : Symfony 7 (API) – hébergé sur un serveur mutualisé🔗 Frontend : Astro.js – déployé sur Netlify avec un webhook de build automatique

📖 Table des matières

    🚀 Aperçu du projet
    🛠️ Technologies utilisées
    ⚙️ Installation et Configuration
    🔄 Workflow CI/CD (Mise à jour & Déploiement)
    📂 Structure du projet
    ✅ Fonctionnalités principales
    📌 To-Do List
    🔑 Gestion des tokens OAuth2
    📜 Licence

🚀 Aperçu du projet

    ebhook Netlify.L'authentification est gérée par OAuth2.

🛠️ Technologies utilisées

    🔹 Backend (API) :

        Symfony 7 – Framework PHP pour gérer l’API

        Doctrine ORM – Gestion de la base de données

        MariaDB – Base de données SQL

        OAuth2 Server – Authentification via OAuth2

        JWT Auth – Sécurisation de l’API

EasyAdmin – Administration des contenus

    🔹 Frontend (Portfolio) :

        Astro.js – Framework pour générer un site statique

        Tailwind CSS + DaisyUI – Styling moderne et responsive

        Fetch API – Récupération des données du backend

        Netlify – Hébergement et build automatique

🔹 CI/CD & Hébergement :

        GitHub Actions – Gestion des mises à jour automatiques

        Netlify Build Hooks – Déclenchement automatique du build

        Docker (local) – Conteneurisation de Symfony en dev

⚙️ Installation et Configuration

    1️⃣ Cloner le projet

        git clone https://github.com/fabry44/portfolio-api.git
        git clone https://github.com/fabry44/portfolio-astro.git

    2️⃣ Backend : Installation Symfony

        cd portfolio-api
        composer install
        cp .env.example .env  # Configurer les variables d'environnement
        php bin/console doctrine:database:create
        php bin/console doctrine:migrations:migrate
        php bin/console cache:clear
        symfony serve

    3️⃣ Frontend : Installation Astro

        cd ../portfolio-astro
        npm install
        npm run dev

    🔄 Workflow CI/CD (Mise à jour & Déploiement)

        🚀 Mise à jour du contenu

            Mise à jour de la base de données (backend Symfony).

            Génération du fichier data.json.

            Push du fichier data.json sur GitHub.

            Déclenchement automatique du build Netlify via webhook.

        ⚡ Commande pour forcer la mise à jour

            curl -X POST -d {} https://api.netlify.com/build_hooks/LE_TOKEN_NETLIFY

📂 Structure du projet

📁 backend-symfony/        # API Symfony
    ├── src/                   # Logique métier
    ├── public/                # Accès public
    ├── var/                   # Cache & logs
    ├── .env                   # Configuration
    ├── composer.json          # Dépendances PHP
    ├── docker-compose.yml     # Docker
    ├── Dockerfile             # Configuration Docker pour la production
    ├── docker.sh              # Script pour gérer Docker en dev
    └── apache.conf            # Configuration Apache

✅ Fonctionnalités principales

    ✔️ Formulaire de contact consommé par Astro.js
    ✔️ Backend sécurisé avec JWT et OAuth2
    ✔️ Admin Dashboard via EasyAdmin pour gérer le contenu
    ✔️ Stockage des données en JSON pour un rendu rapide
    ✔️ Déploiement automatisé via GitHub Actions & Netlify Webhooks
    ✔️ Utilisation de Docker en dev
    ✔️ Gestion des styles avec Tailwind

📌 To-Do List

    🔹 Améliorer la sécurité de l’API avec des scopes OAuth2🔹 Ajouter des tests unitaires pour l’API🔹 Ajouter une fonctionnalité de commentaires sur les projets

🔑 Gestion des tokens OAuth2

🎟️ Génération des clés OAuth2

    mkdir -p config/jwt
    openssl genpkey -algorithm RSA -out config/jwt/private.pem -aes256
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

    Ajouter la passphrase dans .env :

    OAUTH_PASSPHRASE=ma_passphrase

🔐 Créer un client OAuth2

    php bin/console league:oauth2-server:create-client "myportfolio" --scope=portfolio --grant-type=client_credentials

🔑 Obtenir un token d'accès

    curl -X POST http://localhost:8000/token \
        -H "Content-Type: application/x-www-form-urlencoded" \
        -d "grant_type=client_credentials" \
        -d "client_id=TON_CLIENT_ID" \
        -d "client_secret=TON_SECRET" \
        -d "scope=portfolio"

📜 Licence

    Ce projet est sous licence MIT – vous pouvez le modifier et l’utiliser librement.