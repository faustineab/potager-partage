<?php
namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VacancyRepository")
 */
class Vacancy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user"})
     * @Groups({"login"})
     */
    private $id;
    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user","vacancy"})
     * @Groups({"login"})
     */
    private $startDate;
    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user","vacancy"})
     * @Groups({"login"})
     */
    private $endDate;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="vacancies")
     * @ORM\JoinColumn(nullable=false, unique=false)
     * @Groups({"vacancy"})
     * @Groups({"remplacement"})
     * @Groups({"login"})
     */
    private $user;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VacancySubstitute", mappedBy="vacancy", orphanRemoval=true)
     * @Groups({"vacancy"})
     */
    private $vacancySubstitutes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Garden", inversedBy="vacancies")
     * @Groups({"vacancy"})
     */
    private $garden;

    public function __construct()
    {
        $this->vacancySubstitutes = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
    /**
     * @return array 
     */

    public function getNotAvailableDays()
    {
        $notAvailableDays = [];

        foreach ($this->vacancySubstitutes as $vacancySubstitute) {

            // je calcul les jours qui se trouvent entre la date d'arrivée et de départ

            $resultat = range(
                $vacancySubstitute->getStartDate()->getTimestamp(),
                $vacancySubstitute->getEndDate()->getTimestamp(),

                24 * 60 * 60
            ); // nbr de secondes entre les dates d'arrivée et de départ

            $days = array_map(function ($dayTimestamp) {
                $day = new \DateTime(date('Y-m-d', $dayTimestamp));
                return $day->format('Y-m-d H:i:s');
            }, $resultat); // transformation du tableau résultat(seconde) en tableau (jours)
            $notAvailableDays = array_merge($notAvailableDays, $days);
        };

        return  $notAvailableDays;
    }

    public function getAvailableDays()
    {
        $availableDays = [];


        // je calcul les jours qui se trouvent entre la date de début d'absence et de retour

        $resultat = range(
            $this->getStartDate()->getTimestamp(),
            $this->getEndDate()->getTimestamp(),
            24 * 60 * 60
        ); // nbr de secondes entre les dates d'arrivée et de départ

        $days = array_map(function ($dayTimestamp) {

            $day = new \DateTime(date('Y-m-d', $dayTimestamp));
            return $day->format('Y-m-d H:i:s');
        }, $resultat); // transformation du tableau résultat(seconde) en tableau (jours)

        $availableDays = array_merge($availableDays, $days);

        return  $availableDays;
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
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
    /**
     * @return Collection|VacancySubstitute[]
     */
    public function getVacancySubstitutes(): Collection
    {
        return $this->vacancySubstitutes;
    }
    public function addVacancySubstitute(VacancySubstitute $vacancySubstitute): self
    {
        if (!$this->vacancySubstitutes->contains($vacancySubstitute)) {
            $this->vacancySubstitutes[] = $vacancySubstitute;
            $vacancySubstitute->setVacancy($this);
        }
        return $this;
    }
    public function removeVacancySubstitute(VacancySubstitute $vacancySubstitute): self
    {
        if ($this->vacancySubstitutes->contains($vacancySubstitute)) {
            $this->vacancySubstitutes->removeElement($vacancySubstitute);
            // set the owning side to null (unless already changed)
            if ($vacancySubstitute->getVacancy() === $this) {
                $vacancySubstitute->setVacancy(null);
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
