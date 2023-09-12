<?php

namespace App\Entity;

use App\Repository\PostingViewsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostingViewsRepository::class)]
class PostingViews
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ipAdress = null;

    #[ORM\ManyToOne(inversedBy: 'views')]
    private ?Posting $posting = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIpAdress(): ?string
    {
        return $this->ipAdress;
    }

    public function setIpAdress(?string $ipAdress): static
    {
        $this->ipAdress = $ipAdress;

        return $this;
    }

    public function getPosting(): ?Posting
    {
        return $this->posting;
    }

    public function setPosting(?Posting $posting): static
    {
        $this->posting = $posting;

        return $this;
    }
}
