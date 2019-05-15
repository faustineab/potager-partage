<?php

namespace App\Entity;

use DateInterval;
use App\Entity\IsPlantedOn;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VegetableRepository")
 */
class Vegetable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"vegetable"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"is_planted_on", "vegetable", "marketoffer"})
     */
    private $name;

    // Type integer = date interval = datetime + integer EN JOURS
    /**
     * @ORM\Column(type="integer")
     * @Groups({"is_planted_on", "vegetable"})
     */
    private $water_irrigation_interval;

    // Type integer = date interval = datetime + integer EN SEMAINES
    /**
     * @ORM\Column(type="integer")
     * @Groups({"is_planted_on", "vegetable"})
     */
    private $growing_interval;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\IsPlantedOn", mappedBy="vegetable")
     */
    private $isPlantedOns;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"is_planted_on", "vegetable"})
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MarketOffer", mappedBy="vegetable")
     */
    private $marketOffers;

    public function __construct()
    {
        $this->isPlantedOns = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->marketOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getWaterIrrigationInterval(): ?int
    {
        return $this->water_irrigation_interval;
    }

    public function setWaterIrrigationInterval(int $water_irrigation_interval): self
    {
        $this->water_irrigation_interval = $water_irrigation_interval;

        return $this;
    }

    public function getGrowingInterval(): ?int
    {
        return $this->growing_interval;
    }

    public function setGrowingInterval(int $growing_interval): self
    {
        $this->growing_interval = $growing_interval;

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

    /**
     * @return Collection|IsPlantedOn[]
     */
    public function getIsPlantedOns(): Collection
    {
        return $this->isPlantedOns;
    }

    public function addIsPlantedOn(IsPlantedOn $isPlantedOn): self
    {
        if (!$this->isPlantedOns->contains($isPlantedOn)) {
            $this->isPlantedOns[] = $isPlantedOn;
            $isPlantedOn->setVegetable($this);
        }

        return $this;
    }

    public function removeIsPlantedOn(IsPlantedOn $isPlantedOn): self
    {
        if ($this->isPlantedOns->contains($isPlantedOn)) {
            $this->isPlantedOns->removeElement($isPlantedOn);
            // set the owning side to null (unless already changed)
            if ($isPlantedOn->getVegetable() === $this) {
                $isPlantedOn->setVegetable(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|MarketOffer[]
     */
    public function getMarketOffers(): Collection
    {
        return $this->marketOffers;
    }

    public function addMarketOffer(MarketOffer $marketOffer): self
    {
        if (!$this->marketOffers->contains($marketOffer)) {
            $this->marketOffers[] = $marketOffer;
            $marketOffer->setVegetable($this);
        }

        return $this;
    }

    public function removeMarketOffer(MarketOffer $marketOffer): self
    {
        if ($this->marketOffers->contains($marketOffer)) {
            $this->marketOffers->removeElement($marketOffer);
            // set the owning side to null (unless already changed)
            if ($marketOffer->getVegetable() === $this) {
                $marketOffer->setVegetable(null);
            }
        }

        return $this;
    }
}
