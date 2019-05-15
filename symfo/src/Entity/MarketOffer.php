<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarketOfferRepository")
 */
class MarketOffer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"marketoffer"})
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"marketoffer"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"marketoffer"})
     */
    private $unity;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"marketoffer"})
     */
    private $pickup_date;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"marketoffer"})
     */
    private $pickup_place;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"marketoffer"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="marketOffers",cascade={"persist"})
     * @Groups({"marketoffer"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vegetable", inversedBy="marketOffers",cascade={"persist"})
     * @Groups({"marketoffer"})
     */
    private $vegetable;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MarketOrder", mappedBy="market_offer")
     */
    private $marketOrders;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Garden", inversedBy="marketOffers",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"marketoffer"})
     */
    private $garden;

    public function __toString()
       {
           return $this->garden;
       }

    public function __construct()
    {
        $this->marketOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnity(): ?string
    {
        return $this->unity;
    }

    public function setUnity(string $unity): self
    {
        $this->unity = $unity;

        return $this;
    }

    public function getPickupDate(): ?\DateTimeInterface
    {
        return $this->pickup_date;
    }

    public function setPickupDate(?\DateTimeInterface $pickup_date): self
    {
        $this->pickup_date = $pickup_date;

        return $this;
    }

    public function getPickupPlace(): ?string
    {
        return $this->pickup_place;
    }

    public function setPickupPlace(?string $pickup_place): self
    {
        $this->pickup_place = $pickup_place;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVegetable(): ?Vegetable
    {
        return $this->vegetable;
    }

    public function setVegetable(?Vegetable $vegetable): self
    {
        $this->vegetable = $vegetable;

        return $this;
    }

    /**
     * @return Collection|MarketOrder[]
     */
    public function getMarketOrders(): Collection
    {
        return $this->marketOrders;
    }

    public function addMarketOrder(MarketOrder $marketOrder): self
    {
        if (!$this->marketOrders->contains($marketOrder)) {
            $this->marketOrders[] = $marketOrder;
            $marketOrder->setMarketOffer($this);
        }

        return $this;
    }

    public function removeMarketOrder(MarketOrder $marketOrder): self
    {
        if ($this->marketOrders->contains($marketOrder)) {
            $this->marketOrders->removeElement($marketOrder);
            // set the owning side to null (unless already changed)
            if ($marketOrder->getMarketOffer() === $this) {
                $marketOrder->setMarketOffer(null);
            }
        }

        return $this;
    }

    public function getGarden(): ?Garden
    {
        return $this->garden;
    }

    public function setGarden(?Garden $garden): self
    {
        $this->garden = $garden;

        return $this;
    }
}
