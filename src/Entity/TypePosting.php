<?php

namespace App\Entity;

use App\Repository\TypePostingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypePostingRepository::class)]
class TypePosting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'typePosting', targetEntity: Posting::class)]
    private Collection $posting;

    #[ORM\ManyToMany(targetEntity: Compagny::class, mappedBy: 'typeSearch')]
    private Collection $companies;

    #[ORM\ManyToMany(targetEntity: Expert::class, mappedBy: 'typeJob')]
    private Collection $experts;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->posting = new ArrayCollection();
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

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Posting>
     */
    public function getPosting(): Collection
    {
        return $this->posting;
    }

    public function addPosting(Posting $posting): static
    {
        if (!$this->posting->contains($posting)) {
            $this->posting->add($posting);
            $posting->setTypePosting($this);
        }

        return $this;
    }

    public function removePosting(Posting $posting): static
    {
        if ($this->posting->removeElement($posting)) {
            // set the owning side to null (unless already changed)
            if ($posting->getTypePosting() === $this) {
                $posting->setTypePosting(null);
            }
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
            $company->addTypeSearch($this);
        }

        return $this;
    }

    public function removeCompany(Compagny $company): static
    {
        if ($this->companies->removeElement($company)) {
            $company->removeTypeSearch($this);
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
            $expert->addTypeJob($this);
        }

        return $this;
    }

    public function removeExpert(Expert $expert): static
    {
        if ($this->experts->removeElement($expert)) {
            $expert->removeTypeJob($this);
        }

        return $this;
    }
}
