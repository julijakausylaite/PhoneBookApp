<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeleteContactController extends AbstractController
{
    #[Route('/contact/delete/{id}', name: 'delete_contact')]
    public function delete(
        int $id,
        EntityManagerInterface $entityManager,
        ContactRepository $contactRepository
    ): RedirectResponse {

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $contact = $contactRepository->find($id);

        if (!$contact) {
            $this->addFlash('error', 'Contact not found.');
            return $this->redirectToRoute('home');
        }

        $entityManager->remove($contact);
        $entityManager->flush();

        $this->addFlash('success', 'Contact successfully deleted.');

        return $this->redirectToRoute('home');
    }

}
