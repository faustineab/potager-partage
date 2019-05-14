<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IsPlantedOnRepository")
 */
class IsPlantedOn
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $seedling_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $irrigation_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plot", inversedBy="isPlantedOns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vegetable", inversedBy="isPlantedOns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vegetable;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $harvestDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sprayStatus;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->sprayStatus = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeedlingDate(): ?\DateTimeInterface
    {
        return $this->seedling_date;
    }

    public function setSeedlingDate(\DateTimeInterface $seedling_date): self
    {
        $this->seedling_date = $seedling_date;

        return $this;
    }

    public function getIrrigationDate(): ?\DateTimeInterface
    {
        return $this->irrigation_date;
    }

    public function setIrrigationDate(?\DateTimeInterface $irrigation_date): self
    {
        $this->irrigation_date = $irrigation_date;

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

    public function getPlot(): ?Plot
    {
        return $this->plot;
    }

    public function setPlot(?Plot $plot): self
    {
        $this->plot = $plot;

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

    public function getHarvestDate(): ?\DateTimeInterface
    {
        return $this->harvestDate;
    }

    public function setHarvestDate(?\DateTimeInterface $harvestDate): self
    {
        $this->harvestDate = $harvestDate;

        return $this;
    }

    public function getSprayStatus(): ?bool
    {
        return $this->sprayStatus;
    }

    public function setSprayStatus(bool $sprayStatus): self
    {
        $this->sprayStatus = $sprayStatus;

        return $this;
    }
}
