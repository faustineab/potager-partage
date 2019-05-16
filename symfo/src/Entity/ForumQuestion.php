<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumQuestionRepository")
 */
class ForumQuestion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"forum_questions"})
     * @Groups({"forum_tags"})
     * @Groups({"user"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"forum_questions"})
     * @Groups({"forum_tags"})
     * @Groups({"user"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"forum_questions"})
     * @Groups({"forum_tags"})
     * @Groups({"user"})
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"forum_questions"})
     * @Groups({"forum_tags"})
     * @Groups({"user"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"forum_questions"})
     * @Groups({"forum_tags"})
     * @Groups({"user"})
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumAnswer", mappedBy="question", orphanRemoval=true)
     */
    private $answers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ForumTag", mappedBy="questions")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forumQuestions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"forum_questions"})
     * @Groups({"forum_tags"})
     * @Groups({"user"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Garden", inversedBy="forumQuestions")
     */
    private $garden;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->createdAt = new \Datetime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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
     * @return Collection|ForumAnswer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(ForumAnswer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(ForumAnswer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ForumTag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(ForumTag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addQuestion($this);
        }

        return $this;
    }

    public function removeTag(ForumTag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeQuestion($this);
        }

        return $this;
    }

    public function __toString()
    {
        $this->answers;
        $this->tags;
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
