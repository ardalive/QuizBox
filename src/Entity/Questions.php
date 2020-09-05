<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionsRepository::class)
 */
class Questions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $QuestionBody;

    /**
     * @ORM\ManyToMany(targetEntity=Quiz::class, inversedBy="questionID")
     */
    private $quizID;

    public function __construct()
    {
        $this->quizID = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionBody(): ?string
    {
        return $this->QuestionBody;
    }

    public function setQuestionBody(string $QuestionBody): self
    {
        $this->QuestionBody = $QuestionBody;

        return $this;
    }

    /**
     * @return Collection|Quiz[]
     */
    public function getQuizID(): Collection
    {
        return $this->quizID;
    }

    public function addQuizID(Quiz $quizID): self
    {
        if (!$this->quizID->contains($quizID)) {
            $this->quizID[] = $quizID;
        }

        return $this;
    }

    public function removeQuizID(Quiz $quizID): self
    {
        if ($this->quizID->contains($quizID)) {
            $this->quizID->removeElement($quizID);
        }

        return $this;
    }
}
