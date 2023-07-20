<?php

namespace App\Entity;

use App\Repository\PackNameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackNameRepository::class)]
class PackName
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $valability = null;

    #[ORM\OneToMany(mappedBy: 'packName', targetEntity: Pack::class)]
    private Collection $pack;

    public function __construct()
    {
        $this->pack = new ArrayCollection();
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getValability(): ?string
    {
        return $this->valability;
    }

    public function setValability(?string $valability): static
    {
        $this->valability = $valability;

        return $this;
    }

    /**
     * @return Collection<int, Pack>
     */
    public function getPack(): Collection
    {
        return $this->pack;
    }

    public function addPack(Pack $pack): static
    {
        if (!$this->pack->contains($pack)) {
            $this->pack->add($pack);
            $pack->setPackName($this);
        }

        return $this;
    }

    public function removePack(Pack $pack): static
    {
        if ($this->pack->removeElement($pack)) {
            // set the owning side to null (unless already changed)
            if ($pack->getPackName() === $this) {
                $pack->setPackName(null);
            }
        }

        return $this;
    }
}
