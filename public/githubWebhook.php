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

$payload = file_get_contents('php://input');

// Gestion stricte de l'encodage UTF-8 (important selon la doc officielle)
$payloadUtf8 = mb_convert_encoding($payload, 'UTF-8', 'UTF-8');

// Calcul strictement conforme à la doc GitHub
$signatureLocale = 'sha256=' . hash_hmac('sha256', $payloadUtf8, $secret);

// Vérification sécurisée (selon GitHub : timingSafeEqual)
if (!hash_equals($signatureLocale, $signatureGithub)) {
    http_response_code(403);
    exit("Accès refusé : signature invalide. GitHub : [$signatureGithub] Locale : [$signatureLocale]");
}

// Si tout est valide, exécution des commandes de déploiement
$output = [];
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && git pull origin main 2>&1', $output);
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php composer.phar install --no-dev --optimize-autoloader 2>&1', $output);
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php bin/console doctrine:migrations:migrate -n 2>&1', $output);
exec('cd /home/u120012058/domains/dashboard.fabien-roy.fr/public_html/Portfolio-API && php bin/console cache:clear 2>&1', $output);

echo implode("\n", $output);
