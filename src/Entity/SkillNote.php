<?php

namespace App\Entity;

use App\Repository\SkillNoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SkillNoteRepository::class)]
class SkillNote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['identity'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['identity'])]
    private ?int $note = null;

    #[ORM\ManyToOne(inversedBy: 'skillNotes')]
    private ?Identity $identity = null;

    #[ORM\ManyToOne(inversedBy: 'skillNotes')]
    #[Groups(['identity'])]
    private ?TechnicalSkill $technicalSkill = null;

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

    public function getTechnicalSkill(): ?TechnicalSkill
    {
        return $this->technicalSkill;
    }

    public function setTechnicalSkill(?TechnicalSkill $technicalSkill): static
    {
        $this->technicalSkill = $technicalSkill;

        return $this;
    }
}
