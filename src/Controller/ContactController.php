<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ContactType;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

//        $user = $this->getUser();
//        if (!$user) {
//            return $this->redirectToRoute('app_login');
//        }

        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            throw new \LogicException('The logged-in user is not a valid User entity.');
        }

        $contact = new Contact();
        $contact->setAuthor($user);

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $entityManager->persist($contact);
            $entityManager->flush();
            $this->addFlash('success', 'Contact added successfully.');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Add new contact',
            'submit_button' => 'Add contact',
            'shared_with' => '',
        ]);

    }
}
