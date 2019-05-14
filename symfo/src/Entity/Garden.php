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
     * @Groups({"garden_register"})
     * @Groups({"user"})
     * 
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Groups({"garden_register","garden_get","garden_edit", "plot","user"})
     */
    private $name;
    /**
     * @ORM\Column(type="text")
     * @Groups({"garden_get","garden_edit","user"})
     */
    private $address;
    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({"garden_get","garden_edit","user"})
     */
    private $zipcode;
    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"garden_get","garden_edit","user"})
     */
    private $city;
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"garden_get","garden_edit","user"})
     */
    private $address_specificities;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"garden_get","garden_edit"})
     */
    private $meters;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"garden_get","garden_edit"})
     */
    private $number_plots_row;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"garden_get","garden_edit"})
     */
    private $number_plots_column;
    /**
     * @ORM\Column(type="datetime")
     * @Groups({"garden_get"})
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
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Plot", mappedBy="garden", orphanRemoval=true)
     */
    private $plots;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vacancy", mappedBy="garden")
     */
    private $vacancies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="garden")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumQuestion", mappedBy="garden")
     */
    private $forumQuestions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumTag", mappedBy="garden")
     */
    private $forumTags;



    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->plots = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
        $this->vacancies = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->forumQuestions = new ArrayCollection();
        $this->forumTags = new ArrayCollection();
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
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }
        return $this;
    }
    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
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
            $this->vacancies[] = $vacancy;
            $vacancy->setGarden($this);
        }

        return $this;
    }

    public function removeVacancy(Vacancy $vacancy): self
    {
        if ($this->vacancies->contains($vacancy)) {
            $this->vacancies->removeElement($vacancy);
            // set the owning side to null (unless already changed)
            if ($vacancy->getGarden() === $this) {
                $vacancy->setGarden(null);
            }
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
            $event->setGarden($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getGarden() === $this) {
                $event->setGarden(null);
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
            $forumQuestion->setGarden($this);
        }

        return $this;
    }

    public function removeForumQuestion(ForumQuestion $forumQuestion): self
    {
        if ($this->forumQuestions->contains($forumQuestion)) {
            $this->forumQuestions->removeElement($forumQuestion);
            // set the owning side to null (unless already changed)
            if ($forumQuestion->getGarden() === $this) {
                $forumQuestion->setGarden(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ForumTag[]
     */
    public function getForumTags(): Collection
    {
        return $this->forumTags;
    }

    public function addForumTag(ForumTag $forumTag): self
    {
        if (!$this->forumTags->contains($forumTag)) {
            $this->forumTags[] = $forumTag;
            $forumTag->setGarden($this);
        }

        return $this;
    }

    public function removeForumTag(ForumTag $forumTag): self
    {
        if ($this->forumTags->contains($forumTag)) {
            $this->forumTags->removeElement($forumTag);
            // set the owning side to null (unless already changed)
            if ($forumTag->getGarden() === $this) {
                $forumTag->setGarden(null);
            }
        }

        return $this;
    }
}
