<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\IdentityRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Serializable;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IdentityRepository::class)]
#[Vich\Uploadable]
class Identity implements Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['identity'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['identity'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['identity'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['identity'])]
    private ?string $avatar = null;

    #[ORM\Column(nullable: true)]
    private ?bool $mainColor = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['identity'])]
    private ?string $bio = null;

    #[ORM\ManyToOne(inversedBy: 'identities')]
    private ?Account $account = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['identity'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $templateProfile = null;

    #[ORM\ManyToMany(targetEntity: AIcores::class, inversedBy: 'identities')]
    #[Groups(['identity'])]
    private Collection $aicores;

    #[ORM\OneToOne(inversedBy: 'identity', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Sector::class, inversedBy: 'identity')]
    #[Groups(['identity'])]
    private Collection $sectors;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['identity'])]
    private ?string $country = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['identity'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['identity'])]
    private ?string $username = null;

    #[Vich\UploadableField(mapping: 'cv_expert', fileNameProperty: 'fileName')]
    #[Groups(['identity'])]
    private ?File $file = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['identity', 'message'])]
    private ?string $fileName = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Groups(['identity'])]
    private ?string $tarif = null;

    #[ORM\OneToMany(mappedBy: 'identity', targetEntity: Experience::class, cascade: ['persist', 'remove'])]
    #[Groups(['identity'])]
    private Collection $experiences;

    #[ORM\OneToMany(mappedBy: 'identity', targetEntity: Language::class, cascade: ['persist', 'remove'])]
    #[Groups(['identity'])]
    private Collection $languages;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['identity'])]
    private ?string $phone = null;

    #[ORM\ManyToMany(targetEntity: AINote::class, mappedBy: 'identities')]
    private Collection $aINotes;

    #[ORM\OneToMany(mappedBy: 'identity', targetEntity: Note::class)]
    #[Groups(['identity'])]
    private Collection $notes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cv = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\OneToMany(mappedBy: 'identity', targetEntity: Social::class)]
    private Collection $socials;

    #[ORM\OneToMany(mappedBy: 'identity', targetEntity: Pack::class)]
    private Collection $packs;

    #[ORM\OneToOne(mappedBy: 'identity', cascade: ['persist', 'remove'])]
    private ?Compagny $compagny = null;

    #[ORM\OneToMany(mappedBy: 'identity', targetEntity: Commande::class)]
    private Collection $commandes;


    public function __construct()
    {
        $this->aicores = new ArrayCollection();
        $this->sectors = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->aINotes = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->socials = new ArrayCollection();
        $this->packs = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->firstName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function isMainColor(): ?bool
    {
        return $this->mainColor;
    }

    public function setMainColor(?bool $mainColor): static
    {
        $this->mainColor = $mainColor;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

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

    public function getTemplateProfile(): ?string
    {
        return $this->templateProfile;
    }

    public function setTemplateProfile(?string $templateProfile): static
    {
        $this->templateProfile = $templateProfile;

        return $this;
    }

    /**
     * @return Collection<int, AIcores>
     */
    public function getAicores(): Collection
    {
        return $this->aicores;
    }

    public function addAicore(AIcores $aicore): static
    {
        if (!$this->aicores->contains($aicore)) {
            $this->aicores->add($aicore);
        }

        return $this;
    }

    public function removeAicore(AIcores $aicore): static
    {
        $this->aicores->removeElement($aicore);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Sector>
     */
    public function getSectors(): Collection
    {
        return $this->sectors;
    }

    public function addSector(Sector $sector): static
    {
        if (!$this->sectors->contains($sector)) {
            $this->sectors->add($sector);
            $sector->addIdentity($this);
        }

        return $this;
    }

    public function removeSector(Sector $sector): static
    {
        if ($this->sectors->removeElement($sector)) {
            $sector->removeIdentity($this);
        }

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }

    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): static
    {
        $this->fileName = $fileName;

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

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): static
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
            $experience->setIdentity($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): static
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getIdentity() === $this) {
                $experience->setIdentity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): static
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
            $language->setIdentity($this);
        }

        return $this;
    }

    public function removeLanguage(Language $language): static
    {
        if ($this->languages->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getIdentity() === $this) {
                $language->setIdentity(null);
            }
        }

        return $this;
    }
    
    public function serialize()
    {
        $this->fileName = base64_encode($this->fileName);
    }

    public function unserialize($serialized)
    {
        $this->fileName = base64_decode($this->fileName);

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

    /**
     * @return Collection<int, AINote>
     */
    public function getAINotes(): Collection
    {
        return $this->aINotes;
    }

    public function addAINote(AINote $aINote): static
    {
        if (!$this->aINotes->contains($aINote)) {
            $this->aINotes->add($aINote);
            $aINote->addIdentity($this);
        }

        return $this;
    }

    public function removeAINote(AINote $aINote): static
    {
        if ($this->aINotes->removeElement($aINote)) {
            $aINote->removeIdentity($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setIdentity($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getIdentity() === $this) {
                $note->setIdentity(null);
            }
        }

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, Social>
     */
    public function getSocials(): Collection
    {
        return $this->socials;
    }

    public function addSocial(Social $social): static
    {
        if (!$this->socials->contains($social)) {
            $this->socials->add($social);
            $social->setIdentity($this);
        }

        return $this;
    }

    public function removeSocial(Social $social): static
    {
        if ($this->socials->removeElement($social)) {
            // set the owning side to null (unless already changed)
            if ($social->getIdentity() === $this) {
                $social->setIdentity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pack>
     */
    public function getPacks(): Collection
    {
        return $this->packs;
    }

    public function addPack(Pack $pack): static
    {
        if (!$this->packs->contains($pack)) {
            $this->packs->add($pack);
            $pack->setIdentity($this);
        }

        return $this;
    }

    public function removePack(Pack $pack): static
    {
        if ($this->packs->removeElement($pack)) {
            // set the owning side to null (unless already changed)
            if ($pack->getIdentity() === $this) {
                $pack->setIdentity(null);
            }
        }

        return $this;
    }

    public function getCompagny(): ?Compagny
    {
        return $this->compagny;
    }

    public function setCompagny(?Compagny $compagny): static
    {
        // unset the owning side of the relation if necessary
        if ($compagny === null && $this->compagny !== null) {
            $this->compagny->setIdentity(null);
        }

        // set the owning side of the relation if necessary
        if ($compagny !== null && $compagny->getIdentity() !== $this) {
            $compagny->setIdentity($this);
        }

        $this->compagny = $compagny;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setIdentity($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getIdentity() === $this) {
                $commande->setIdentity(null);
            }
        }

        return $this;
    }

}
