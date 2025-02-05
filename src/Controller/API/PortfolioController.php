<?php

namespace App\Controller\API;

use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use App\Repository\FormationRepository;
use App\Repository\TechnologyRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PortfolioController extends AbstractController{
    #[Route('/api/portfolio-data', name: 'api_portfolio', methods: ['GET'])]
    public function getPortfolioData(
        UserRepository $userRepository,
        FormationRepository $formationRepository,
        ProjectRepository $projectRepository,
        TechnologyRepository $technologyRepository
    ): JsonResponse {
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
