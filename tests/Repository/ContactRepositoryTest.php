<?php

namespace Repository;

use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ContactRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ContactRepositoryTest extends KernelTestCase
{
    private EntityManager $entityManager;
    private ContactRepository $contactRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    private ManagerRegistry $managerRegistry;

    protected function setUp(): void
    {
        parent::setUp();

        $config = ORMSetup::createConfiguration(true);
        $driver = new AttributeDriver([__DIR__ . '/../../src/Entity']);
        $config->setMetadataDriverImpl($driver);

        $dbParams = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        $conn = DriverManager::getConnection($dbParams, $config);
        $this->entityManager = new EntityManager($conn, $config);

        $this->managerRegistry = $this->createRegistry();

        $schemaTool = new SchemaTool($this->entityManager);
        $allMetadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($allMetadata);

        $this->contactRepository = new ContactRepository($this->managerRegistry);

        $this->userPasswordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
    }

    private function createRegistry(): ManagerRegistry
    {
        $registry = $this->createMock(ManagerRegistry::class);

        $registry->method('getManagerForClass')
            ->willReturn($this->entityManager);

        return $registry;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        unset($this->entityManager);
        unset($this->contactRepository);
        unset($this->userPasswordHasher);
        unset($this->managerRegistry);
    }


    public function testFindSharedContactsForUser(): void
    {
        $user1 = $this->createUser('user1', 'password');
        $user2 = $this->createUser('user2', 'password');

        $contact1 = new Contact();
        $contact1->setContactName('Contact 1');
        $contact1->setPhone('+370622222222');
        $contact1->setAuthor($user1);
        $contact1->shareWith($user2);
        $this->entityManager->persist($contact1);

        $contact2 = new Contact();
        $contact2->setContactName('Contact 2');
        $contact2->setPhone('+37061111111');
        $contact2->setAuthor($user1);
        $this->entityManager->persist($contact2);

        $this->entityManager->flush();

        $sharedContacts = $this->contactRepository->findSharedContactsForUser($user2->getId());
        $this->assertCount(1, $sharedContacts);
        $this->assertEquals('Contact 1', $sharedContacts[0]->getContactName());
    }

    private function createUser(string $username, string $password): User
    {
        $user = new User();
        $user->setUsername($username);

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

}

