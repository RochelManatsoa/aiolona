<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['identity'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['identity'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['identity'])]
    private ?string $company = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['identity'])]
    private ?bool $currently = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['identity'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['identity'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['identity'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'experiences', cascade: ['persist', 'remove'])]
    private ?Identity $identity = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['identity'])]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['identity'])]
    private ?string $country = null;

    #[ORM\ManyToMany(targetEntity: AIcores::class, inversedBy: 'experiences')]
    private Collection $skills;

    #[ORM\ManyToMany(targetEntity: TechnicalSkill::class, mappedBy: 'experience')]
    private Collection $technicalSkills;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->technicalSkills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function isCurrently(): ?bool
    {
        return $this->currently;
    }

    public function setCurrently(?bool $currently): static
    {
        $this->currently = $currently;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

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

    public function getIdentity(): ?Identity
    {
        return $this->identity;
    }

    public function setIdentity(?Identity $identity): static
    {
        $this->identity = $identity;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, AIcores>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(AIcores $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(AIcores $skill): static
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    /**
     * @return Collection<int, TechnicalSkill>
     */
    public function getTechnicalSkills(): Collection
    {
        return $this->technicalSkills;
    }

    public function addTechnicalSkill(TechnicalSkill $technicalSkill): static
    {
        if (!$this->technicalSkills->contains($technicalSkill)) {
            $this->technicalSkills->add($technicalSkill);
            $technicalSkill->addExperience($this);
        }

        return $this;
    }

    public function removeTechnicalSkill(TechnicalSkill $technicalSkill): static
    {
        if ($this->technicalSkills->removeElement($technicalSkill)) {
            $technicalSkill->removeExperience($this);
        }

        return $this;
    }
}
