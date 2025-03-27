<?php

namespace App\Controller\Api;

use App\Service\NetlifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class NetlifyController extends AbstractController
{
    private NetlifyService $netlifyService;

    public function __construct(NetlifyService $netlifyService)
    {
        $this->netlifyService = $netlifyService;
    }

    #[Route('/api-netlify-build', name: 'api_netlify_build', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function triggerBuild(): JsonResponse
    {
        $this->netlifyService->triggerBuild();

        return new JsonResponse(['message' => 'Rebuild Netlify lancé'], 200);
    }
}
