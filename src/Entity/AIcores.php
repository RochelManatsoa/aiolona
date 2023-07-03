<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AIcoresRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: AIcoresRepository::class)]
class AIcores
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
    private ?string $url = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: AIcategory::class, mappedBy: 'aicores')]
    private Collection $aIcategories;

    #[ORM\ManyToMany(targetEntity: Identity::class, mappedBy: 'aicores')]
    private Collection $identities;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->aIcategories = new ArrayCollection();
        $this->identities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $name): static
    {
        $this->name = $name;

        return $this;
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, AIcategory>
     */
    public function getAIcategories(): Collection
    {
        return $this->aIcategories;
    }

    public function addAIcategory(AIcategory $aIcategory): static
    {
        if (!$this->aIcategories->contains($aIcategory)) {
            $this->aIcategories->add($aIcategory);
            $aIcategory->addAicore($this);
        }

        return $this;
    }

    public function removeAIcategory(AIcategory $aIcategory): static
    {
        if ($this->aIcategories->removeElement($aIcategory)) {
            $aIcategory->removeAicore($this);
        }

        return $this;
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
            $identity->addAicore($this);
        }

        return $this;
    }

    public function removeIdentity(Identity $identity): static
    {
        if ($this->identities->removeElement($identity)) {
            $identity->removeAicore($this);
        }

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
