<?php

namespace App\Entity;

use App\Repository\CompagnyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompagnyRepository::class)]
class Compagny
{
    use \App\Manager\Trait\CompanyTrait;

    const SIZE_XSMALL = 'XS';
    const SIZE_SMALL = 'SM';
    const SIZE_MEDIUM = 'MD';
    const SIZE_LARGE = 'LG';
    const SIZE_XLARGE = 'XL';

    const CHOICE_SIZE = [        
         '1 to 49' => self::SIZE_XSMALL ,
         '50 to 149' => self::SIZE_SMALL ,
         '150 to 249' => self::SIZE_MEDIUM ,
         '250 to 499' => self::SIZE_LARGE ,
         '500 +' => self::SIZE_XLARGE ,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $size = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\OneToOne(inversedBy: 'compagny', cascade: ['persist', 'remove'])]
    private ?Identity $identity = null;

    #[ORM\OneToMany(mappedBy: 'compagny', targetEntity: Posting::class)]
    private Collection $posting;

    public function __toString()
    {
        return $this->getName();
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

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

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

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

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
            $posting->setCompagny($this);
        }

        return $this;
    }

    public function removePosting(Posting $posting): static
    {
        if ($this->posting->removeElement($posting)) {
            // set the owning side to null (unless already changed)
            if ($posting->getCompagny() === $this) {
                $posting->setCompagny(null);
            }
        }

        return $this;
    }
}
