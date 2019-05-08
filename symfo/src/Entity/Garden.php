<?php

namespace App\Entity;

use App\Entity\Plot;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\GardenRepository")
 */
class Garden
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("garden_get")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups("garden_get")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups("garden_get")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups("garden_get")
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $city;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("garden_get")
     */
    private $address_specificities;

    /**
     * @ORM\Column(type="integer")
     * @Groups("garden_get")
     */
    private $meters;

    /**
     * @ORM\Column(type="integer")
     * @Groups("garden_get")
     */
    private $number_plots_row;

    /**
     * @ORM\Column(type="integer")
     * @Groups("garden_get")
     */
    private $number_plots_column;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("garden_get")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("garden_get")
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="gardens")
     * @Groups("garden_get")
     * 
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Plot", mappedBy="garden")
     * @Groups("garden_get")
     */
    private $plots;

    

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->plots = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddressSpecificities(): ?string
    {
        return $this->address_specificities;
    }

    public function setAddressSpecificities(?string $address_specificities): self
    {
        $this->address_specificities = $address_specificities;

        return $this;
    }

    public function getMeters(): ?int
    {
        return $this->meters;
    }

    public function setMeters(int $meters): self
    {
        $this->meters = $meters;

        return $this;
    }

    public function getNumberPlotsRow(): ?int
    {
        return $this->number_plots_row;
    }

    public function setNumberPlotsRow(int $number_plots_row): self
    {
        $this->number_plots_row = $number_plots_row;

        return $this;
    }

    public function getNumberPlotsColumn(): ?int
    {
        return $this->number_plots_column;
    }

    public function setNumberPlotsColumn(int $number_plots_column): self
    {
        $this->number_plots_column = $number_plots_column;

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
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|Plot[]
     */
    public function getPlots(): Collection
    {
        return $this->plots;
    }

    public function addPlot(Plot $plot): self
    {
        if (!$this->plots->contains($plot)) {
            $this->plots[] = $plot;
            $plot->setGarden($this);
        }

        return $this;
    }

    public function removePlot(Plot $plot): self
    {
        if ($this->plots->contains($plot)) {
            $this->plots->removeElement($plot);
            // set the owning side to null (unless already changed)
            if ($plot->getGarden() === $this) {
                $plot->setGarden(null);
            }
        }

        return $this;
    }
}
