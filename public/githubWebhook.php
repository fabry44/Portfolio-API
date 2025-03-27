<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

$secret = $_ENV['GITHUB_WEBHOOK_SECRET'] ?? null;
$headers = getallheaders();
$signatureGithub = $headers['X-Hub-Signature-256'] ?? null;

$payload = file_get_contents('php://input');

// Vérifie la présence de la signature
if (!$signatureGithub) {
    http_response_code(403);
    exit('Signature manquante');
}

// Calcule la signature locale
$localSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

// Comparaison sécurisée
if (!hash_equals($localSignature, $signatureGithub)) {
    file_put_contents(__DIR__.'/../github_signature_debug.log', "GitHub : [$signatureGithub]\nLocal : [$localSignature]\n");
    http_response_code(403);
    exit("Accès refusé : signature invalide.");
}

// Si tout est OK, exécuter les commandes
$output = [];
$dir = '/home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API';
exec("cd $dir && git pull origin main 2>&1", $output);
exec("cd $dir && php composer.phar install --no-dev --optimize-autoloader 2>&1", $output);
exec("cd $dir && php bin/console doctrine:migrations:migrate -n 2>&1", $output);
exec("cd $dir && php bin/console cache:clear 2>&1", $output);

echo implode("\n", $output);
