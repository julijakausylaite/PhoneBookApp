<?php

namespace App\Controller;

use App\Form\ShareContactType;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShareContactController extends AbstractController
{
    #[Route('/contact/{id}/share', name: 'contact_share', methods: ['POST'])]
    public function shareContact(
        int $id,
        Request $request,
        ContactRepository $contactRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {

        $contact = $contactRepository->find($id);
        if (!$contact) {
            return $this->json(['error' => 'Contact not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $userId = $data['user_id'] ?? null;

        $user = $userRepository->find($userId);
        if (!$user) {
            return $this->json(['error' => 'User not found'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $contact->shareWith($user);
        $entityManager->flush();

        return $this->json(['message' => 'Contact shared successfully']);
    }

}
