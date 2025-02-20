<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class WebhookController extends AbstractController
{
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    #[Route('/admin/rebuild-astro', name: 'admin_rebuild_astro', methods: ['GET'])]
    public function rebuildAstro(): JsonResponse
    {
        $astroWebhookUrl = 'http://localhost:4322/api/webhook';
        // dump($astroWebhookUrl);
        // dump($_ENV['REBUILD_SECRET']);
        // die();
        try {
            $response = $this->httpClient->request('POST', $astroWebhookUrl, [
                'headers' => [
                    'X-API-SECRET' => '9ffa89b83de02d7c70a29ced0f17556222adbbdb558218ebb28b5bb47011871c',
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                return new JsonResponse(['error' => 'Échec du rebuild Astro'], 500);
            }

            return new JsonResponse(['message' => 'Rebuild Astro déclenché avec succès'], 200);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors du rebuild Astro: ' . $e->getMessage());
            return new JsonResponse(['error' => 'Erreur interne'], 500);
        }
    }

}
