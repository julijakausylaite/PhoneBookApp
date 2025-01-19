<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UnshareContactController extends AbstractController
{
    #[Route('/contact/{id}/unshare/{userId}', name: 'contact_unshare', methods: ['POST'])]
    public function unshare(
        int $id,
        int $userId,
        EntityManagerInterface $entityManager
    ): Response {

        $contact = $entityManager->getRepository(Contact::class)->find($id);
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$contact || !$user) {
            throw $this->createNotFoundException('Contact or User not found.');
        }

        if ($contact->getAuthor() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You do not have permission to unshare this contact.');
        }

        $contact->removeShare($user);
        $entityManager->flush();

        return $this->redirectToRoute('edit_contact', ['id' => $id]);
    }

}
