# !/bin/bash
set -e

echo "nstallation des dépendances (Composer)..."
composer install --prefer-dist --no-progress 

echo "Création (ou vérification) de la base de données..."
php bin/console doctrine:database:create --if-not-exists

echo "Exécution des migrations..."
# php bin/console make:migration
php bin/console doctrine:migrations:migrate --no-interaction


echo "Importation des données..."
php bin/console app:import-techno
php bin/console app:import-data

echo "Nettoyage du cache..."
php bin/console cache:clear --env=dev --no-interaction

echo "Lancement d'Apache..."
exec apache2-foreground
