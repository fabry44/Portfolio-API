<?php

namespace App\Controller\Api;

use App\Service\ResumeDataService;
use App\Service\GitHubService;
use App\Service\PortfolioDataService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PortfolioController extends AbstractController
{
    private ResumeDataService $resumeDataService;
    private PortfolioDataService $portfolioDataService;
    private GitHubService $gitHubService;
    private LoggerInterface $logger;

    public function __construct(
        ResumeDataService $resumeDataService,
        PortfolioDataService $portfolioDataService,
        GitHubService $gitHubService,
        LoggerInterface $logger

    ) {
        $this->resumeDataService = $resumeDataService;
        $this->portfolioDataService = $portfolioDataService;
        $this->gitHubService = $gitHubService;
        $this->logger = $logger;
    }

    /**
     * @Route("/api/update-portfolio", name="update_portfolio", methods={"POST"})
     */
    #[Route('/api/update-portfolio', name: 'api_update_portfolio')]
    public function updatePortfolio(): JsonResponse
    {
        try {
            // Étape 1 : Générer le fichier JSON depuis la BDD
            $jsonPathResume = $this->resumeDataService->generateJsonFile();
            $this->logger->info("Fichier JSON généré : " . $jsonPathResume);
            $jsonPathPortfolio = $this->portfolioDataService->generateJsonFile();
            $this->logger->info("Fichier JSON généré : " . $jsonPathPortfolio);

            // Étape 2 : Pousser les json sur GitHub
            $jsonContentResume = file_get_contents($jsonPathResume);
            $this->gitHubService->updateFile(
                'resume.json',
                $jsonContentResume,
                'Mise à jour automatique de resume.json',
                'Mise à jour du portfolio'
            );
            $this->logger->info("resume.json poussé sur GitHub");

            $jsonContentPortfolio = file_get_contents($jsonPathPortfolio);
            $this->gitHubService->updateFile(
                'src/data/data.json',
                $jsonContentPortfolio,
                'Mise à jour automatique de portfolio.json',
                'Mise à jour du portfolio'
            );
            $this->logger->info("portfolio.json poussé sur GitHub");

            // Étape 3 : Pousser les images sur GitHub
            // Upload de la photo de profil de l'utilisateur
            $userPhotoPath = $this->getParameter('kernel.project_dir') . '/uploads/user/photo.jpg';
            if (file_exists($userPhotoPath)) {
                $this->gitHubService->uploadImage(
                    'public/profile-photo.jpg',
                    $userPhotoPath,
                    'Mise à jour de la photo de profil'
                );
                $this->logger->info("Photo de profil poussée sur GitHub");
            }

            // Upload des images des projets
            $projectImagesDir = $this->getParameter('kernel.project_dir') . '/public/uploads/projects/';
            $projectImages = scandir($projectImagesDir);

            foreach ($projectImages as $image) {
                if (in_array($image, ['.', '..'])) continue;

                $localPath = $projectImagesDir . $image;
                $repoPath = 'public/projects/' . $image;

                $this->gitHubService->uploadImage(
                    $repoPath,
                    $localPath,
                    "Mise à jour de l'image du projet $image"
                );
                $this->logger->info("Image du projet poussée sur GitHub : $image");
            }


            return new JsonResponse([
                'status' => 'success',
                'message' => 'Mise à jour du repository réussie',
            ], Response::HTTP_OK);

        

        } catch (\Exception $e) {
            $this->logger->error("Erreur dans la mise à jour du repository : " . $e->getMessage());
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour du repository',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
