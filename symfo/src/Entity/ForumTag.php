<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumTagRepository")
 */
class ForumTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"forum_tags", "user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"forum_question_index", "forum_question_show", "forum_tags", "user"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"forum_tags", "user"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"forum_tags", "user"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ForumQuestion", inversedBy="tags")
     * @Groups({"forum_question_index", "forum_tags"})
     */
    private $questions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Garden", inversedBy="forumTags")
     * @Groups({"forum_question_index"})
     */
    private $garden;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->createdAt = new \DateTime();
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

    /**
     * @return Collection|ForumQuestion[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(ForumQuestion $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
        }

        return $this;
    }

    public function removeQuestion(ForumQuestion $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
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
