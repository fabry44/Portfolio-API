<?php

namespace App\Controller\Api;

use App\Entity\ContactRequest;
use App\Form\ContactRequestType;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

class ApiContactController extends AbstractController
{
    #[Route('/api/contact', name: 'api_contact', methods: ['POST'])]
    public function submitContact(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        HtmlSanitizerInterface $htmlSanitizer
    ): JsonResponse {
        // Décodage des données JSON envoyées par le frontend (Astro)
        $data = json_decode($request->getContent(), true);

        // Vérification si les données sont bien reçues
        if (!$data) {
            return new JsonResponse(['error' => 'Données invalides ou format incorrect'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Création et soumission du formulaire avec les données
        $contactRequest = new ContactRequest();
        $form = $this->createForm(ContactRequestType::class, $contactRequest);
        $form->submit($data);

        // Vérification des erreurs de validation
        if (!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Nettoyage du message pour éviter les injections HTML et XSS
        $sanitizedMessage = $htmlSanitizer->sanitize($contactRequest->getFirstName());
        $contactRequest->setFirstName($sanitizedMessage);
        $sanitizedMessage = $htmlSanitizer->sanitize($contactRequest->getLastName());
        $contactRequest->setLastName($sanitizedMessage);
        $sanitizedMessage = $htmlSanitizer->sanitize($contactRequest->getEmail());
        $contactRequest->setEmail($sanitizedMessage);
        $sanitizedMessage = $htmlSanitizer->sanitize($contactRequest->getPhone());
        $contactRequest->setPhone($sanitizedMessage);
        $sanitizedMessage = $htmlSanitizer->sanitize($contactRequest->getMessage());
        $contactRequest->setMessage($sanitizedMessage);


        // Sauvegarde dans la base de données
        $entityManager->persist($contactRequest);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Demande de contact soumise avec succès.'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/contact/form', name: 'api_contact_form', methods: ['GET'])]
    public function getContactFormStructure(): JsonResponse
    {
        // Génération du formulaire Symfony pour récupérer les champs
        $form = $this->createForm(ContactRequestType::class);
        $formView = $form->createView();

        $formFields = [];

        foreach ($formView as $child) {
            $formFields[] = [
                'name' => $child->vars['full_name'],
                'type' => $child->vars['block_prefixes'][1], // Input, Textarea, Email, etc.
                'label' => $child->vars['label'],
                'required' => $child->vars['required'],
            ];
        }

        return new JsonResponse(['fields' => $formFields]);
    }
}