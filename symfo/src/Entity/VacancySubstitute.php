<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VacancySubstituteRepository")
 */
class VacancySubstitute
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"vacancy"})
     * @Groups({"remplacement"})
     * 
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"vacancy"})
     * @Groups({"remplacement"})
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vacancy", inversedBy="vacancySubstitutes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"remplacement"})
     */
    private $vacancy;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="vacancySubstitute")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"vacancy"})
     * 
     */
    private $user;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function isBookableDate()
    {

        //  1/ dates qui ne sont pas disponibles

        $notAvailableDays = $this->vacancy->getNotAvailableDays();

        // 2/ Comparer les dates choisies avec les dates impossibles 

        $substituteDays = $this->getDays();

        // $formatDay = function ($day) {
        //     return $day->format('Y-m-d');
        // };

        // tableau contenant les chaines de caractère des journées 
        // $days = array_map($formatDay, $substituteDays);

        // $notAvailable = array_map($formatDay, $notAvailableDays);

        foreach ($substituteDays as $substituteDay) {
            if (array_search($substituteDay, $notAvailableDays) !== false)
                return false;
        }
        return true;
    }

    /** 
     * Permet de récupérer un tableau des journées qui correspondent à ma remplacement 
     * @return array Représente un tableau d'object DateTime des jours de remplacement 
     */

    public function getDays()
    {
        $resultat = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24 * 60 * 60
        ); // nbr de secondes entre les dates de début et de fin de remplacement

        $days = array_map(function ($dayTimestamp) {
            $day = new \DateTime(date('Y-m-d', $dayTimestamp));
            return $day->format('Y-m-d H:i:s');
        }, $resultat);


        return  $days;
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

    public function getVacancy(): ?Vacancy
    {
        return $this->vacancy;
    }

    public function setVacancy(?Vacancy $vacancy): self
    {
        $this->vacancy = $vacancy;

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
}
