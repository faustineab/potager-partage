<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlotRepository")
 */
class Plot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user","plot","garden_get"})
     * @Groups({"is_planted_on"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","plot","garden_get"})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user","plot"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"user","plot"})
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Garden", inversedBy="plots", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups("plot")
     */
    private $garden;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="plots" , cascade={"persist"})
     * @Groups({"user","plot"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\IsPlantedOn", mappedBy="plot")
     */
    private $isPlantedOns;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
        $this->isPlantedOns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    public function getGarden(): ?Garden
    {
        return $this->garden;
    }

    public function setGarden(?Garden $garden): self
    {
        $this->garden = $garden;

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
            $isPlantedOn->setPlot($this);
        }

        return $this;
    }

    public function removeIsPlantedOn(IsPlantedOn $isPlantedOn): self
    {
        if ($this->isPlantedOns->contains($isPlantedOn)) {
            $this->isPlantedOns->removeElement($isPlantedOn);
            // set the owning side to null (unless already changed)
            if ($isPlantedOn->getPlot() === $this) {
                $isPlantedOn->setPlot(null);
            }
        }

        return $this;
    }
}
