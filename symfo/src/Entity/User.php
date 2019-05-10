<?php

namespace App\Entity;

use App\Entity\Plot;
use App\Entity\Role;
use App\Entity\Event;
use App\Entity\Garden;
use App\Entity\Vacancy;
use App\Entity\ForumAnswer;
use App\Entity\ForumQuestion;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\VacancySubstitute;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event"})
     * @Groups({"vacancy"})
     * @Groups({"remplacement"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;


    /**
     * @ORM\Column(type="integer")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Garden", mappedBy="user")
     */
    private $gardens;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Plot", mappedBy="user")
     */
    private $plots;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumAnswer", mappedBy="user")
     */
    private $forumAnswers;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumQuestion", mappedBy="user")
     */
    private $forumQuestions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vacancy", mappedBy="user", orphanRemoval=true)
     */
    private $vacancies;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\VacancySubstitute", mappedBy="user", cascade={"persist", "remove"})
     */
    private $vacancySubstitute;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="user", orphanRemoval=true)
     */
    private $events;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users", cascade={"persist"})
     */
    private $roles;


    public function __construct()
    {

        $this->gardens = new ArrayCollection();
        $this->plots = new ArrayCollection();
        $this->forumAnswers = new ArrayCollection();
        $this->forumQuestions = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->vacancies = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
        $this->roles = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

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

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Garden[]
     */
    public function getGardens(): Collection
    {
        return $this->gardens;
    }
    public function addGarden(Garden $garden): self
    {
        if (!$this->gardens->contains($garden)) {
            $this->gardens[] = $garden;
            $garden->addUser($this);
        }
        return $this;
    }



    public function removeGarden(Garden $garden): self
    {
        if ($this->gardens->contains($garden)) {
            $this->gardens->removeElement($garden);
            $garden->removeUser($this);
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
            $plot->setUser($this);
        }
        return $this;
    }
    public function removePlot(Plot $plot): self
    {
        if ($this->plots->contains($plot)) {
            $this->plots->removeElement($plot);
            // set the owning side to null (unless already changed)
            if ($plot->getUser() === $this) {
                $plot->setUser(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection|ForumAnswer[]
     */
    public function getForumAnswers(): Collection
    {
        return $this->forumAnswers;
    }
    public function addForumAnswer(ForumAnswer $forumAnswer): self
    {
        if (!$this->forumAnswers->contains($forumAnswer)) {
            $this->forumAnswers[] = $forumAnswer;
            $forumAnswer->setUser($this);
        }
        return $this;
    }
    public function removeForumAnswer(ForumAnswer $forumAnswer): self
    {
        if ($this->forumAnswers->contains($forumAnswer)) {
            $this->forumAnswers->removeElement($forumAnswer);
            // set the owning side to null (unless already changed)
            if ($forumAnswer->getUser() === $this) {
                $forumAnswer->setUser(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection|ForumQuestion[]
     */
    public function getForumQuestions(): Collection
    {
        return $this->forumQuestions;
    }
    public function addForumQuestion(ForumQuestion $forumQuestion): self
    {
        if (!$this->forumQuestions->contains($forumQuestion)) {
            $this->forumQuestions[] = $forumQuestion;
            $forumQuestion->setUser($this);
        }
        return $this;
    }
    public function removeForumQuestion(ForumQuestion $forumQuestion): self
    {
        if ($this->forumQuestions->contains($forumQuestion)) {
            $this->forumQuestions->removeElement($forumQuestion);
            // set the owning side to null (unless already changed)
            if ($forumQuestion->getUser() === $this) {
                $forumQuestion->setUser(null);
            }
        }
        return $this;
    }


    //   public function __toString()
    //    {
    //        return $this->gardens;
    //    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|Vacancy[]
     */
    public function getVacancies(): Collection
    {
        return $this->vacancies;
    }

    public function addVacancy(Vacancy $vacancy): self
    {
        if (!$this->vacancies->contains($vacancy)) {
            $this->vacancys[] = $vacancy;
            $vacancy->setUser($this);
        }

        return $this;
    }

    public function removeVacancy(Vacancy $vacancy): self
    {
        if ($this->vacancies->contains($vacancy)) {
            $this->events->removeElement($vacancy);
            // set the owning side to null (unless already changed)
            if ($vacancy->getUser() === $this) {
                $vacancy->setUser(null);
            }
        }

        return $this;
    }

    public function getVacancySubstitute(): ?VacancySubstitute
    {
        return $this->vacancySubstitute;
    }

    public function setVacancySubstitute(VacancySubstitute $vacancySubstitute): self
    {
        $this->vacancySubstitute = $vacancySubstitute;

        // set the owning side of the relation if necessary
        if ($this !== $vacancySubstitute->getUser()) {
            $vacancySubstitute->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setUser($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getUser() === $this) {
                $event->setUser(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {

        $roles = $this->roles->map(function ($role) {
            return $role->getTitle();
        })->toArray();
        $roles[] = 'ROLE_USER';
        return $roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addUser($this);
        }
        return $this;
    }
    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
            $role->removeUser($this);
        }
        return $this;
    }
}
