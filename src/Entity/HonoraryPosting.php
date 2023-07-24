<?php

namespace App\Entity;

use App\Repository\HonoraryPostingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HonoraryPostingRepository::class)]
class HonoraryPosting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'honoraryPosting', targetEntity: Posting::class)]
    private Collection $posting;
    
    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->posting = new ArrayCollection();
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
            $posting->setHonoraryPosting($this);
        }

        return $this;
    }

    public function removePosting(Posting $posting): static
    {
        if ($this->posting->removeElement($posting)) {
            // set the owning side to null (unless already changed)
            if ($posting->getHonoraryPosting() === $this) {
                $posting->setHonoraryPosting(null);
            }
        }

        return $this;
    }
}
