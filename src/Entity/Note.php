<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['identity'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['identity'])]
    private ?int $note = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    private ?Identity $identity = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[Groups(['identity'])]
    private ?AIcores $aiCore = null;

    public function __toString()
    {
        return $this->note;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getIdentity(): ?Identity
    {
        return $this->identity;
    }

    public function setIdentity(?Identity $identity): static
    {
        $this->identity = $identity;

        return $this;
    }

    public function getAiCore(): ?AIcores
    {
        return $this->aiCore;
    }

    public function setAiCore(?AIcores $aiCore): static
    {
        $this->aiCore = $aiCore;

        return $this;
    }
}
