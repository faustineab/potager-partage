<?php

namespace App\Entity;

use DateInterval;
use App\Entity\IsPlantedOn;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VegetableRepository")
 */
class Vegetable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $name;

    // Type integer = date interval = datetime + integer 
    /**
     * @ORM\Column(type="integer")
     */
    private $water_irrigation_interval;

    // Type integer = date interval = datetime + integer 
    /**
     * @ORM\Column(type="integer")
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
     */
    private $image;

    public function __toString()
    {
        return $this->water_irrigation_interval;
    }

    public function __construct()
    {
        $this->isPlantedOns = new ArrayCollection();
        $this->created_at = new \DateTime();
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

    public function getWaterIrrigationInterval(): ?\DateInterval
    {
        return $this->water_irrigation_interval;
    }

    public function setWaterIrrigationInterval(\DateInterval $water_irrigation_interval): self
    {
        $this->water_irrigation_interval = $water_irrigation_interval;

        return $this;
    }

    public function getGrowingInterval(): ?\DateInterval
    {
        return $this->growing_interval;
    }

    public function setGrowingInterval(\DateInterval $growing_interval): self
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
}
