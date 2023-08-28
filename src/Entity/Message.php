<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['message'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['message'])]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['message'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isRead = null;

    #[ORM\ManyToOne(inversedBy: 'sent')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['message'])]
    private ?User $sender = null;

    #[ORM\ManyToOne(inversedBy: 'received')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $recipient = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->isRead = false;
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(?bool $isRead): static
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }
}
