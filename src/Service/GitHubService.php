<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubService
{
    private HttpClientInterface $httpClient;
    private string $githubToken;
    private string $repoOwner;
    private string $repoName;
    private string $branch;
    private NetlifyService $netlifyService;

    public function __construct(HttpClientInterface $httpClient, string $githubToken, string $repoOwner, string $repoName, string $branch, NetlifyService $netlifyService)
    {
        $this->httpClient = $httpClient;
        $this->githubToken = $githubToken;
        $this->repoOwner = $repoOwner;
        $this->repoName = $repoName;
        $this->branch = $branch;
        $this->netlifyService = $netlifyService;
    }

    public function updateFile(string $filePath, string $content, string $commitMessage): void
    {
        // Get the current SHA of the file
        $response = $this->httpClient->request('GET', "https://api.github.com/repos/$this->repoOwner/$this->repoName/contents/$filePath?ref=$this->branch", [
            'headers' => [
                'Authorization' => "token $this->githubToken",
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);

        $sha = json_decode($response->getContent(), true)['sha'] ?? null;

        // Update the file
        $this->httpClient->request('PUT', "https://api.github.com/repos/$this->repoOwner/$this->repoName/contents/$filePath", [
            'headers' => [
                'Authorization' => "token $this->githubToken",
                'Accept' => 'application/vnd.github.v3+json',
            ],
            'json' => [
                'message' => $commitMessage,
                'content' => base64_encode($content),
                'sha' => $sha,
                'branch' => $this->branch,
            ],
        ]);
    }

    public function uploadImage(string $filePath, string $localFilePath, string $commitMessage): void
    {
        // Lire le fichier en base64
        $imageContent = base64_encode(file_get_contents($localFilePath));

        // Vérifier si le fichier existe déjà dans le repo
        $response = $this->httpClient->request('GET', "https://api.github.com/repos/$this->repoOwner/$this->repoName/contents/$filePath?ref=$this->branch", [
            'headers' => [
                'Authorization' => "token $this->githubToken",
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);

        $sha = null;
        if ($response->getStatusCode() === 200) {
            $sha = json_decode($response->getContent(), true)['sha'] ?? null;
        }

        // Envoyer l'image sur GitHub
        $this->httpClient->request('PUT', "https://api.github.com/repos/$this->repoOwner/$this->repoName/contents/$filePath", [
            'headers' => [
                'Authorization' => "token $this->githubToken",
                'Accept' => 'application/vnd.github.v3+json',
            ],
            'json' => [
                'message' => $commitMessage,
                'content' => $imageContent,
                'sha' => $sha,
                'branch' => $this->branch,
            ],
        ]);
    }

}
