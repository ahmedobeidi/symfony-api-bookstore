<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\RandomBooksController;
use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            name: 'random_books',
            uriTemplate: '/books/random',
            controller: RandomBooksController::class,
            normalizationContext: ['groups' => ['book:read']],
            paginationEnabled: false,
        ), 
        new GetCollection(
            normalizationContext: ['groups' => ['book:read']],
            paginationItemsPerPage: 8,
        ),
        new Get(
            normalizationContext: ['groups' => ['book:read']]       
        ),
        new Post(
            denormalizationContext: ['groups' => ['book:create']]
        ),
        new Patch(
            denormalizationContext: ['groups' => ['book:update']]
        ),
        new Delete()
    ],
)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['book:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read', 'book:create', 'book:update'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read', 'book:create', 'book:update'])]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['book:read', 'book:create', 'book:update'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['book:read', 'book:create', 'book:update'])]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups(['book:read'])]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column]
    #[Groups(['book:read', 'book:update'])]
    private ?bool $available = null;

    public function __construct()
    {
        $this->publishedAt = new \DateTimeImmutable();
        $this->available = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }
}
