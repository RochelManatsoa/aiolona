<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use App\Repository\SectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SectorRepository::class)]
class Sector
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['identity'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['identity'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['identity'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Identity::class, mappedBy: 'sectors')]
    private Collection $identity;

    #[ORM\ManyToMany(targetEntity: Posting::class, mappedBy: 'sector')]
    private Collection $postings;

    #[ORM\ManyToMany(targetEntity: Compagny::class, mappedBy: 'sector')]
    private Collection $companies;

    #[ORM\ManyToMany(targetEntity: Expert::class, mappedBy: 'sector')]
    private Collection $experts;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->identity = new ArrayCollection();
        $this->postings = new ArrayCollection();
        $this->companies = new ArrayCollection();
        $this->experts = new ArrayCollection();
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

    /**
     * @return Collection<int, Posting>
     */
    public function getPostings(): Collection
    {
        return $this->postings;
    }

    public function addPosting(Posting $posting): static
    {
        if (!$this->postings->contains($posting)) {
            $this->postings->add($posting);
            $posting->addSector($this);
        }

        return $this;
    }

    public function removePosting(Posting $posting): static
    {
        if ($this->postings->removeElement($posting)) {
            $posting->removeSector($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Compagny>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Compagny $company): static
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $company->addSector($this);
        }

        return $this;
    }

    public function removeCompany(Compagny $company): static
    {
        if ($this->companies->removeElement($company)) {
            $company->removeSector($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Expert>
     */
    public function getExperts(): Collection
    {
        return $this->experts;
    }

    public function addExpert(Expert $expert): static
    {
        if (!$this->experts->contains($expert)) {
            $this->experts->add($expert);
            $expert->addSector($this);
        }

        return $this;
    }

    public function removeExpert(Expert $expert): static
    {
        if ($this->experts->removeElement($expert)) {
            $expert->removeSector($this);
        }

        return $this;
    }
}
