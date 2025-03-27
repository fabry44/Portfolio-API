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

// Payload strictement brut, sans modification
$payload = file_get_contents('php://input');

file_put_contents(__DIR__.'/github_payload.log', $payload);

file_put_contents(__DIR__.'/../github_signature.log', $signatureGithub);

// IMPORTANT : forcer l'encodage UTF-8 et retirer BOM ou espaces
$payload = mb_convert_encoding($payload, 'UTF-8', 'UTF-8');
$payload = trim($payload);

// Calcul précis et strict (GitHub officiel)
$signatureLocale = 'sha256=' . hash_hmac('sha256', $payload, $secret);

if (!hash_equals($localSignature, $signatureGithub)) {
    file_put_contents(__DIR__.'/../debug_signature.log', "GitHub : [$signatureGithub]\nLocale : [$localSignature]\nPayload :\n$payload");
    http_response_code(403);
    exit('Signature invalide');
}

// Si la vérification réussit, exécution des commandes
$output = [];
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && git pull origin main 2>&1', $output);
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php composer.phar install --no-dev --optimize-autoloader 2>&1', $output);
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php bin/console doctrine:migrations:migrate -n 2>&1', $output);
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php bin/console cache:clear 2>&1', $output);

echo implode("\n", $output);
