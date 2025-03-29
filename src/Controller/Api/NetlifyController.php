<?php

namespace App\Controller\Api;

use App\Service\NetlifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;

class NetlifyController extends AbstractController
{
    private NetlifyService $netlifyService;

    public function __construct(NetlifyService $netlifyService)
    {
        $this->netlifyService = $netlifyService;
    }

    #[Route('/api-netlify-build', name: 'api_netlify_build', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function triggerBuild(): Response
    {
        $this->netlifyService->triggerBuild();

        // return new JsonResponse(['message' => 'Rebuild Netlify lancé'], 200);

        // return new JsonResponse([
        //     'status' => 'success',
        //     'message' => 'Rebuild Netlify lancé',
        // ], Response::HTTP_OK);

        // Rendre la vue avec le graphique
        return $this->render('admin/turboFrame/index.html.twig', [
            'status' => 'success',
            'message' => '✅ Rebuild Netlify lancé',
        ]);
        
    }
}
