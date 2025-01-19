<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        ContactRepository $contactRepository,
        UserRepository $userRepository)
    : Response {

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if (!$user instanceof \App\Entity\User) {
            throw new \LogicException('The logged-in user is not a valid User entity.');
        }

        $userId = $user->getId();
        $contacts = $contactRepository->findBy(['author' => $userId]);
        $allUsers = $userRepository->findAllUsernamesAndIds($userId);
        $sharedContacts = $contactRepository->findSharedContactsForUser($user->getId());

        return $this->render('home/homepage.html.twig', [
            'contacts' => $contacts,
            'all_users' => $allUsers,

            'shared_contacts' => $sharedContacts
        ]);

    }
}
