<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NetlifyService
{
    private HttpClientInterface $httpClient;
    private ParameterBagInterface $params;
    private LoggerInterface $logger;
    private string $netlifyHookUrl;

    public function __construct(
        HttpClientInterface $httpClient,
        ParameterBagInterface $params,
        LoggerInterface $logger,
        string $netlifyHookUrl
        
    ) {
        $this->httpClient = $httpClient;
        $this->params = $params;
        $this->logger = $logger;
        $this->netlifyHookUrl =$netlifyHookUrl;
    }

    public function triggerBuild(): void
    {
        

        if (!$this->netlifyHookUrl) {
            $this->logger->error('URL du webhook Netlify non configurée.');
            return;
        }

        try {
            $response = $this->httpClient->request('POST', $this->netlifyHookUrl, [
                'json' => [] // Payload vide
            ]);

            if ($response->getStatusCode() === 200) {
                $this->logger->info('Rebuild Netlify déclenché avec succès.');
            } else {
                $this->logger->error('Échec du rebuild Netlify. Code: ' . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors du déclenchement de Netlify: ' . $e->getMessage());
        }
    }
}
