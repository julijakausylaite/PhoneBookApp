<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ContactType;

class EditContactController extends AbstractController
{
    #[Route('/contact/edit/{id}', name: 'edit_contact')]
    public function edit(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        ContactRepository $contactRepository)
    : Response {

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $contact = $contactRepository->find($id);

        if (!$contact) {
            $this->addFlash('error', 'Contact not found!');
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Contact updated successfully!');
            return $this->redirectToRoute('edit_contact', ['id' => $contact->getId()]);
        }

        return $this->render('contact/contact_form.html.twig', [
            'form' => $form->createView(),
            'contact_id' => $id,
            'title' => 'Edit contact',
            'submit_button' => 'Edit contact',
            'shared_with' => $contact->getSharedWith(),
        ]);
    }
}
