<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProtectedApiController extends AbstractController
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('/api/protected', name: 'api_protected', methods: ['GET'])]
    public function index(): JsonResponse
    {
        // Récupérer le token
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return $this->json(['error' => 'Aucun token trouvé'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Récupérer les informations de l'utilisateur
        $user = $token->getUser();
        $roles = $token->getRoleNames();

        return $this->json([
            'message' => 'Accès autorisé avec un token valide',
            'user_identifier' => $user->getUserIdentifier(),
            'roles' => $roles,
            'raw_token' => $token->getCredentials(), // Affiche le token brut
            'attributes' => $token->getAttributes(), // Affiche tous les attributs stockés dans le token
        ]);
    }
}
