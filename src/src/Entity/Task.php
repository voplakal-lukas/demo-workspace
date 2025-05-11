<?php

namespace App\Entity;

use App\Enum\ListingType;
use App\Enum\ScopeType;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedDate = null;

    #[ORM\Column(enumType: ScopeType::class)]
    private ?ScopeType $scope = null;

    #[ORM\Column(enumType: ListingType::class)]
    private ?ListingType $listing = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(\DateTimeInterface $updatedDate): static
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    public function getScope(): ?ScopeType
    {
        return $this->scope;
    }

    public function setScope(ScopeType $scope): static
    {
        $this->scope = $scope;

        return $this;
    }

    public function getListing(): ?ListingType
    {
        return $this->listing;
    }

    public function setListing(ListingType $listing): static
    {
        $this->listing = $listing;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}
