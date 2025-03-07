<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\State\RandomBookProvider;
use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            name: 'random_books',
            uriTemplate: '/books/random',
            provider: RandomBookProvider::class,
            normalizationContext: ['groups' => ['book:read']],
            paginationEnabled: false,
            security: "is_granted('ROLE_USER')"
        ), 
        new GetCollection(
            normalizationContext: ['groups' => ['book:read']],
            paginationItemsPerPage: 8,
            security: "is_granted('ROLE_USER')"
        ),
        new Get(
            normalizationContext: ['groups' => ['book:read']],
            security: "is_granted('ROLE_USER')"
        ),
        new Post(
            denormalizationContext: ['groups' => ['book:write']],
            security: "is_granted('ROLE_USER')",
        ),
        new Patch(
            denormalizationContext: ['groups' => ['book:update']],
            security: "is_granted('ROLE_USER')",
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
    #[Groups(['book:read', 'book:write', 'book:update'])]
    #[Assert\NotBlank] 
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Title must be at least {{ limit }} characters long',
        maxMessage: 'Title cannot be longer than {{ limit }} characters',
    )]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read', 'book:write', 'book:update'])]
    #[Assert\NotBlank]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['book:read', 'book:write', 'book:update'])]
    #[Assert\Length(
        min: 12,
        minMessage: 'Description must be at least {{ limit }} characters long',
    )]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['book:read', 'book:write', 'book:update'])]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups(['book:read'])]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column]
    #[Groups(['book:read', 'book:update'])]
    #[Assert\Type(
        type: 'boolean',
        message: 'The value {{ value }} is not a valid boolean.'
    )]
    private ?bool $available = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Groups(['book:read', 'book:write', 'book:update'])]
    private ?Category $category = null;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
