<?php

namespace App\Entity;

use App\Repository\PostingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PostingRepository::class)]
class Posting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $desctiption = null;

    #[ORM\ManyToMany(targetEntity: Sector::class, inversedBy: 'postings')]
    private Collection $sector;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tarif = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $number = null;

    #[ORM\Column]
    private ?bool $valid = null;

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $jobId = null;

    #[ORM\Column(nullable: true)]
    private ?bool $plannedDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\ManyToOne(inversedBy: 'posting')]
    private ?TypePosting $typePosting = null;

    #[ORM\ManyToOne(inversedBy: 'posting')]
    private ?HonoraryPosting $honoraryPosting = null;

    #[ORM\ManyToOne(inversedBy: 'posting')]
    private ?Compagny $compagny = null;

    #[ORM\ManyToMany(targetEntity: SchedulePosting::class, inversedBy: 'posting')]
    private Collection $schedulePostings;

    #[ORM\ManyToMany(targetEntity: AIcores::class, inversedBy: 'postings')]
    private Collection $skills;

    #[ORM\OneToMany(mappedBy: 'posting', targetEntity: PostingViews::class)]
    private Collection $views;

    #[ORM\OneToMany(mappedBy: 'posting', targetEntity: Application::class)]
    private Collection $applications;

    public function __construct()
    {
        $this->sector = new ArrayCollection();
        $this->jobId = new Uuid(Uuid::v1());
        $this->schedulePostings = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->views = new ArrayCollection();
        $this->applications = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): static
    {
        $this->valid = $valid;

        return $this;
    }

    public function getJobId(): ?Uuid
    {
        return $this->jobId;
    }

    public function setJobId(Uuid $jobId): static
    {
        $this->jobId = $jobId;

        return $this;
    }

    public function isPlannedDate(): ?bool
    {
        return $this->plannedDate;
    }

    public function setPlannedDate(?bool $plannedDate): static
    {
        $this->plannedDate = $plannedDate;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getTypePosting(): ?TypePosting
    {
        return $this->typePosting;
    }

    public function setTypePosting(?TypePosting $typePosting): static
    {
        $this->typePosting = $typePosting;

        return $this;
    }

    public function getHonoraryPosting(): ?HonoraryPosting
    {
        return $this->honoraryPosting;
    }

    public function setHonoraryPosting(?HonoraryPosting $honoraryPosting): static
    {
        $this->honoraryPosting = $honoraryPosting;

        return $this;
    }

    public function getDesctiption(): ?string
    {
        return $this->desctiption;
    }

    public function setDesctiption(?string $desctiption): static
    {
        $this->desctiption = $desctiption;

        return $this;
    }

    public function getTarif(): ?string
    {
        return $this->tarif;
    }

    public function setTarif(?string $tarif): static
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getCompagny(): ?Compagny
    {
        return $this->compagny;
    }

    public function setCompagny(?Compagny $compagny): static
    {
        $this->compagny = $compagny;

        return $this;
    }

    /**
     * @return Collection<int, SchedulePosting>
     */
    public function getSchedulePostings(): Collection
    {
        return $this->schedulePostings;
    }

    public function addSchedulePosting(SchedulePosting $schedulePosting): static
    {
        if (!$this->schedulePostings->contains($schedulePosting)) {
            $this->schedulePostings->add($schedulePosting);
            $schedulePosting->addPosting($this);
        }

        return $this;
    }

    public function removeSchedulePosting(SchedulePosting $schedulePosting): static
    {
        if ($this->schedulePostings->removeElement($schedulePosting)) {
            $schedulePosting->removePosting($this);
        }

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
     * @return Collection<int, PostingViews>
     */
    public function getViews(): Collection
    {
        return $this->views;
    }

    public function addView(PostingViews $view): static
    {
        if (!$this->views->contains($view)) {
            $this->views->add($view);
            $view->setPosting($this);
        }

        return $this;
    }

    public function removeView(PostingViews $view): static
    {
        if ($this->views->removeElement($view)) {
            // set the owning side to null (unless already changed)
            if ($view->getPosting() === $this) {
                $view->setPosting(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->setPosting($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getPosting() === $this) {
                $application->setPosting(null);
            }
        }

        return $this;
    }
}
