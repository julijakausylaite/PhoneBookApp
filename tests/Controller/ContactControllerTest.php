<?php

namespace Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ContactControllerTest extends WebTestCase{

    protected KernelBrowser $client;
    protected EntityManagerInterface $entityManager;
    protected ContactRepository $contactRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->contactRepository = self::getContainer()->get(ContactRepository::class);
        $this->userPasswordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);
    }

    public function testCreateContact(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', '/contact');
        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Add contact')->form([
            'contact[contactName]' => 'Jonas Jonaitis',
            'contact[phone]' => '+37069874563',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/contact');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert', 'Contact added successfully.');
    }

    private function createUser(): User
    {
        $user = new User();
        $user->setUsername('testuser_' . time());

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            'password'
        );
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
