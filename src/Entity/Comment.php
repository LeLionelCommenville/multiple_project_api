<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use App\Repository\CommentRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\GetCollection;
use APiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiFilter;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource(
    description: 'A Comment',
    paginationItemsPerPage: 10,
    operations: [
        new Get(uriTemplate: '/comment/{id}'),
        new Patch(),
        new GetCollection(),
        new Post(uriTemplate: '/comment/new/'),
        new Put(),
        new Delete(),
    ],
    normalizationContext: [
        'groups' => ['comment:read']
    ],
    denormalizationContext: [
        'groups' => ['comment:write']
    ]
)]
#[ApiFilter(OrderFilter::class, properties:['createdAt' => 'DESC', 'updatedAt' => 'DESC'])]
class Comment
{
    #[Groups(['comment:read'],['comment:write'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Assert\NotBlank]
    #[Groups(['comment:read'],['comment:write'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $commentText = null;

    #[Groups(['comment:read'],['comment:write'])]
    #[Assert\NotBlank]
    #[ORM\Column]
    private ?int $externalId = null;

    #[Groups(['comment:read'])]
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[Groups(['comment:read'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(['comment:read'],['comment:write'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Groups(['comment:read'])]
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $Category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentText(): ?string
    {
        return $this->commentText;
    }

    public function setCommentText(string $commentText): static
    {
        $this->commentText = $commentText;

        return $this;
    }

    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    public function setExternalId(int $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[Groups(['comment:read'])]
    public function getCreatedAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans(['parts' => 2, 'join' => ', ']);
    }

    #[Groups(['comment:read'])]
    public function getUpdatedAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans(['parts' => 2, 'join' => ', ']);
    }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): static
    {
        $this->Category = $Category;

        return $this;
    }
}
