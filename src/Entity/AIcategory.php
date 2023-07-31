<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use App\Repository\AIcategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AIcategoryRepository::class)]
class AIcategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: AIcores::class, inversedBy: 'aIcategories')]
    private Collection $aicores;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->aicores = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

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
    public function getAicores(): Collection
    {
        return $this->aicores;
    }

    public function addAicore(AIcores $aicore): static
    {
        if (!$this->aicores->contains($aicore)) {
            $this->aicores->add($aicore);
        }

        return $this;
    }

    public function removeAicore(AIcores $aicore): static
    {
        $this->aicores->removeElement($aicore);

        return $this;
    }
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateSlug(): void
    {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->name);
    }
}
