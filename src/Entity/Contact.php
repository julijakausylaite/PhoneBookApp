<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: 'contact')]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $contactName = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Regex(
        pattern: '/^\+?[1-9]\d{1,14}$/',
        message: 'Please enter a valid phone number.'
    )]
    private ?string $phone = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author', referencedColumnName: 'id', nullable: false)]
    private ?User $author = null;


    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'sharedContacts')]
    #[ORM\JoinTable(name: 'contact_user')]
    #[ORM\JoinColumn(name: 'contact_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private Collection $sharedWith;

    public function __construct()
    {
        $this->sharedWith = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    public function setContactName(string $contactName): static
    {
        $this->contactName = $contactName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getSharedWith(): Collection
    {
        return $this->sharedWith;
    }

    public function shareWith(User $user): static
    {
        if (!$this->sharedWith->contains($user)) {
            $this->sharedWith->add($user);
        }

        return $this;
    }

    public function removeShare(User $user): static
    {
        $this->sharedWith->removeElement($user);

        return $this;
    }
}
