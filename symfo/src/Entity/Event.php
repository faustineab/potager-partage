<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
<<<<<<< HEAD
     * @Groups({"event", "user"})
=======
     * @Groups({"event"})
     * @Groups({"user"})
     * @Groups({"login"})
>>>>>>> 3a8bd107986d37258626b7f3ba3577826b2b9e1f
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
<<<<<<< HEAD
     * @Groups({"event", "user"})
=======
     * @Groups({"event"})
     * @Groups({"user"})
     * @Groups({"login"})
>>>>>>> 3a8bd107986d37258626b7f3ba3577826b2b9e1f
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
<<<<<<< HEAD
     * @Groups({"event", "user"})
=======
     * @Groups({"event"})
     * @Groups({"user"})
     * @Groups({"login"})
>>>>>>> 3a8bd107986d37258626b7f3ba3577826b2b9e1f
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
<<<<<<< HEAD
     * @Groups({"event", "user"})
=======
     * @Groups({"event"})
     * @Groups({"user"})
     * @Groups({"login"})
>>>>>>> 3a8bd107986d37258626b7f3ba3577826b2b9e1f
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
<<<<<<< HEAD
     * @Groups({"event", "user"})
=======
     * @Groups({"event"})
     * @Groups({"user"})
     * @Groups({"login"})
>>>>>>> 3a8bd107986d37258626b7f3ba3577826b2b9e1f
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"user"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="events")
     * @ORM\JoinColumn(nullable=false, unique = false)
<<<<<<< HEAD
     * @Groups({"event", "user"})
=======
     * @Groups({"event"})
     * @Groups({"user"})
     * @Groups({"login"})
>>>>>>> 3a8bd107986d37258626b7f3ba3577826b2b9e1f
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Garden", inversedBy="events")
     */
    private $garden;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
