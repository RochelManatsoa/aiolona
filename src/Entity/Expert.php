<?php

namespace App\Entity;

use App\Repository\ExpertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpertRepository::class)]
class Expert
{
    use \App\Manager\Trait\ExpertTrait;

    const YEAR_SMALL = 'SM';
    const YEAR_MEDIUM = 'MD';
    const YEAR_LARGE = 'LG';
    const YEAR_XLARGE = 'LG';

    const CHOICE_YEAR = [        
         'Moins d\'1 an' => self::YEAR_SMALL ,
         '1-3 ans' => self::YEAR_MEDIUM ,
         '3-5 ans' => self::YEAR_LARGE ,
         'Plus de 5 ans' => self::YEAR_XLARGE ,
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'expert', cascade: ['persist', 'remove'])]
    private ?Identity $identity = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: Sector::class, inversedBy: 'experts')]
    private Collection $sector;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $years = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localisation = null;

    #[ORM\ManyToMany(targetEntity: TypePosting::class, inversedBy: 'experts')]
    private Collection $typeJob;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $mainSkills = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $aspiration = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $preference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    public function __construct()
    {
        $this->sector = new ArrayCollection();
        $this->typeJob = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Sector>
     */
    public function getSector(): Collection
    {
        return $this->sector;
    }

    public function addSector(Sector $sector): static
    {
        if (!$this->sector->contains($sector)) {
            $this->sector->add($sector);
        }

        return $this;
    }

    public function removeSector(Sector $sector): static
    {
        $this->sector->removeElement($sector);

        return $this;
    }

    public function getYears(): ?string
    {
        return $this->years;
    }

    public function setYears(?string $years): static
    {
        $this->years = $years;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * @return Collection<int, TypePosting>
     */
    public function getTypeJob(): Collection
    {
        return $this->typeJob;
    }

    public function addTypeJob(TypePosting $typeJob): static
    {
        if (!$this->typeJob->contains($typeJob)) {
            $this->typeJob->add($typeJob);
        }

        return $this;
    }

    public function removeTypeJob(TypePosting $typeJob): static
    {
        $this->typeJob->removeElement($typeJob);

        return $this;
    }

    public function getMainSkills(): ?string
    {
        return $this->mainSkills;
    }

    public function setMainSkills(?string $mainSkills): static
    {
        $this->mainSkills = $mainSkills;

        return $this;
    }

    public function getAspiration(): ?string
    {
        return $this->aspiration;
    }

    public function setAspiration(?string $aspiration): static
    {
        $this->aspiration = $aspiration;

        return $this;
    }

    public function getPreference(): ?string
    {
        return $this->preference;
    }

    public function setPreference(?string $preference): static
    {
        $this->preference = $preference;

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
}
