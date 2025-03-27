<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

$secret = getenv('GITHUB_WEBHOOK_SECRET');
$headers = getallheaders();

$signatureGithub = $headers['X-Hub-Signature-256'] ?? '';

if (!$signatureGithub) {
    http_response_code(403);
    exit('Accès refusé : signature GitHub absente.');
}

// Payload réel provenant de GitHub
$payload = file_get_contents('php://input');
$payload = trim($payload, "\n\r");

// Calcul strictement conforme à GitHub
$signatureLocale = 'sha256=' . hash_hmac('sha256', $payload, $secret);

if (!hash_equals($signatureLocale, $signatureGithub)) {
    http_response_code(403);
    exit("Accès refusé : signature invalide. GitHub : [$signatureGithub] Locale : [$signatureLocale]");
}

// Si tout est valide, exécution des commandes
$output = [];
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && git pull origin main 2>&1', $output);
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php composer.phar install --no-dev --optimize-autoloader 2>&1', $output);
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php bin/console doctrine:migrations:migrate -n 2>&1', $output);
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php bin/console cache:clear 2>&1', $output);

echo implode("\n", $output);
