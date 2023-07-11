<?php

namespace App\Entity;

use App\Repository\AINoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AINoteRepository::class)]
class AINote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: AIcores::class, inversedBy: 'aINotes')]
    private Collection $aiCores;

    #[ORM\ManyToMany(targetEntity: Identity::class, inversedBy: 'aINotes')]
    private Collection $identities;

    public function __construct()
    {
        $this->aiCores = new ArrayCollection();
        $this->identities = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, AIcores>
     */
    public function getAiCores(): Collection
    {
        return $this->aiCores;
    }

    /**
     * @return Collection<int, Identity>
     */
    public function getIdentities(): Collection
    {
        return $this->identities;
    }

    public function addIdentity(Identity $identity): static
    {
        if (!$this->identities->contains($identity)) {
            $this->identities->add($identity);
        }

        return $this;
    }

    public function removeIdentity(Identity $identity): static
    {
        $this->identities->removeElement($identity);

        return $this;
    }
}
