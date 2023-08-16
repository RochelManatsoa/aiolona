<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    const STATUS_SUCCEEDED = 'succeeded';
    const STATUS_PENDING = 'pending';
    const STATUS_FAILED = 'failed';
    const STATUS_OPEN = 'open';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Identity $identity = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: StripeTransaction::class)]
    private Collection $stripeTransaction;

    #[ORM\Column]
    private ?int $totalAmount = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shippingAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $billingAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shipmentTrackingNumber = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $shipmentDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $paymentMethod = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeItems::class)]
    private Collection $items;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $paymentIntent = null;

    public function __construct()
    {
        $this->stripeTransaction = new ArrayCollection();
        $this->items = new ArrayCollection();
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

    /**
     * @return Collection<int, StripeTransaction>
     */
    public function getStripeTransaction(): Collection
    {
        return $this->stripeTransaction;
    }

    public function addStripeTransaction(StripeTransaction $stripeTransaction): static
    {
        if (!$this->stripeTransaction->contains($stripeTransaction)) {
            $this->stripeTransaction->add($stripeTransaction);
            $stripeTransaction->setCommande($this);
        }

        return $this;
    }

    public function removeStripeTransaction(StripeTransaction $stripeTransaction): static
    {
        if ($this->stripeTransaction->removeElement($stripeTransaction)) {
            // set the owning side to null (unless already changed)
            if ($stripeTransaction->getCommande() === $this) {
                $stripeTransaction->setCommande(null);
            }
        }

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?string $shippingAddress): static
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?string $billingAddress): static
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getShipmentTrackingNumber(): ?string
    {
        return $this->shipmentTrackingNumber;
    }

    public function setShipmentTrackingNumber(?string $shipmentTrackingNumber): static
    {
        $this->shipmentTrackingNumber = $shipmentTrackingNumber;

        return $this;
    }

    public function getShipmentDate(): ?\DateTimeInterface
    {
        return $this->shipmentDate;
    }

    public function setShipmentDate(?\DateTimeInterface $shipmentDate): static
    {
        $this->shipmentDate = $shipmentDate;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return Collection<int, CommandeItems>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(CommandeItems $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setCommande($this);
        }

        return $this;
    }

    public function removeItem(CommandeItems $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCommande() === $this) {
                $item->setCommande(null);
            }
        }

        return $this;
    }

    public function getPaymentIntent(): ?string
    {
        return $this->paymentIntent;
    }

    public function setPaymentIntent(?string $paymentIntent): static
    {
        $this->paymentIntent = $paymentIntent;

        return $this;
    }
}
