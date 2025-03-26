<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use Symfony\Component\Dotenv\Dotenv;

// Charger Composer
require __DIR__ . '/../vendor/autoload.php';

// Charger le fichier .env pour récupérer le secret
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

$secret = getenv('GITHUB_WEBHOOK_SECRET');

$headers = getallheaders();

// Vérifier la signature GitHub
if (!isset($headers['X-Hub-Signature-256'])) {
    http_response_code(403);
    exit('Accès refusé : signature manquante.');
}

// Récupération propre du payload GitHub
$payload = trim(file_get_contents('php://input'));

// Calcul de la signature locale avec le payload exact
$signatureLocale = 'sha256=' . hash_hmac('sha256', $payload, $secret);

// Vérifie si la signature correspond
if (!hash_equals($signatureLocale, $signatureGithub)) {
    // Affiche clairement les signatures pour le débogage immédiat
    http_response_code(403);
    exit('Accès refusé : signature invalide. ' 
        . "GitHub : [$signatureGithub] " 
        . "Locale : [$signatureLocale]");
}


// Si vérification OK, alors exécuter le déploiement
$output = [];
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && git pull origin main 2>&1', $output);
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php composer.phar install --no-dev --optimize-autoloader 2>&1', $output);
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php bin/console doctrine:migrations:migrate -n 2>&1', $output);
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php bin/console cache:clear 2>&1', $output);

echo implode("\n", $output);
