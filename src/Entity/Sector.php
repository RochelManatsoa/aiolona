<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use App\Repository\SectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SectorRepository::class)]
class Sector
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

    #[ORM\ManyToMany(targetEntity: Identity::class, mappedBy: 'sectors')]
    private Collection $identity;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->identity = new ArrayCollection();
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
     * @return Collection<int, Identity>
     */
    public function getIdentity(): Collection
    {
        return $this->identity;
    }

    public function addIdentity(Identity $identity): static
    {
        if (!$this->identity->contains($identity)) {
            $this->identity->add($identity);
        }

        return $this;
    }

    public function removeIdentity(Identity $identity): static
    {
        $this->identity->removeElement($identity);

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
