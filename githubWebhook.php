<?php

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

// Charge les variables du fichier .env
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

// Récupération propre du secret depuis l'environnement
$secret_token = $_ENV['GITHUB_WEBHOOK_SECRET'];

$headers = getallheaders();

if (!isset($headers['X-Hub-Signature-256'])) {
    exit('Accès refusé.');
}

// Git Pull automatique depuis la branche "main"
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && git pull origin main 2>&1', $output);

// Composer install (optionnel mais recommandé)
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php composer.phar install --no-main --optimize-autoloader 2>&1', $output);

// Migrations Symfony automatiques (optionnel mais conseillé)
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php bin/console doctrine:migrations:migrate -n 2>&1', $output);

// Vide cache Symfony (recommandé)
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php bin/console cache:clear 2>&1', $output);

// Affichage de résultat pour débogage (optionnel)
echo implode("\n", $output);
