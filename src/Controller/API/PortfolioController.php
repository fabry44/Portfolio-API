<?php

namespace App\Controller\API;

use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use App\Repository\FormationRepository;
use App\Repository\TechnologyRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// #[IsGranted('ROLE_OAUTH2_portfolio')] 
final class PortfolioController extends AbstractController{
    #[Route('/api/portfolio-data', name: 'api_portfolio', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function getPortfolioData(
        UserRepository $userRepository,
        FormationRepository $formationRepository,
        TechnologyRepository $technologyRepository,
        ProjectRepository $projectRepository
    ): JsonResponse {
        // Vérifier si l'utilisateur est bien authentifié
        // if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
        //     return $this->json(['error' => 'Authentication required'], 401);
        // }

        $user = $userRepository->findOneBy([]); // On suppose qu'il n'y a qu'un seul utilisateur
        $formations = $formationRepository->findAll();
        $projects = $projectRepository->findAll();
        $technologies = $technologyRepository->findAll();

        return $this->json([
            'user' => $user,
            'formations' => $formations,
            'projects' => $projects,
            'technologies' => $technologies
        ], 200, [], [
            'groups' => ['api.portfolio']
        ]);
    }
}
