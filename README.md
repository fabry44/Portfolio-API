📌 Portfolio-API (Backend)


📂 Projet Full-Stack : API Symfony (backend) & Astro (frontend)
    🔗 Backend : Symfony 7 (API) – hébergé sur un serveur mutualisé
    🔗 Frontend : Astro.js – déployé sur Netlify avec un webhook de build automatique


📖 Table des matières   
    🚀 Aperçu du projet
    🛠️ Technologies utilisées
    ⚙️ Installation et Configuration
    🔄 Workflow CI/CD (Mise à jour & Déploiement)
    📂 Structure du projet
    ✅ Fonctionnalités principales
    📌 To-Do List
    📜 Licence
    🚀 Aperçu du projet



Le backend (Symfony) expose une API JSON (Formulaire de contact) consommée par le frontend (Astro.js).
Les données sont mises à jour dynamiquement via GitHub et un webhook Netlify.


🛠️ Technologies utilisées:

    🔹 Backend (API) :
        Symfony 7 – Framework PHP pour gérer l’API
        Doctrine ORM – Gestion de la base de données
        MariaDB – Base de données SQL
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
        copier .env.example .env  # Configurer les variables d'environnement
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
        Déclenchement automatique du build Netlify via webhook (Build Hook Netlifly).

    ⚡ Commande pour forcer la mise à jour

        rl -X POST -d {} https://api.netlify.com/build_hooks/LE_TOKEN_NETLIFLY


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

    ✔️ Formulaire de contact pour envoyer le formulaire de contact et récupérer les soumissions via le frontend Astro
    ✔️ Backend sécurisé avec JWT et CORS
    ✔️ Admin Dashboard via EasyAdmin pour gérer le contenu
    ✔️ Stockage des données en JSON pour un rendu ultra rapide en static par le frontend Astro
    ✔️ Déploiement automatisé sur Netlify via API GitHub Actions (API GitHub) & Webhooks
    ✔️ Utilisation de Docker en dev pour une config standardisée
    ✔️ Gestion des styles avec Tailwind


📌 To-Do List

    Améliorer la sécurité de l’API avec des scopes OAuth2
    Ajouter des tests unitaires pour l’API
    Ajouter une fonctionnalité de commentaires sur les projets


📜 Licence

    Ce projet est sous licence MIT – vous pouvez le modifier et l’utiliser librement.