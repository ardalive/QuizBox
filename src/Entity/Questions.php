<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     * @Assert\Length(min=3, minMessage = "This value is too short. The body of the question should have 3 characters or more.",)
     *
     * @Assert\Length(max=200, maxMessage="This value is too long. The body of the question should have 200 characters or less.")
     */
    private $QuestionBody;

    /**
     * @ORM\ManyToMany(targetEntity=Quiz::class, inversedBy="questionID", cascade={"persist"})
     * @ORM\JoinTable(name="questions_quiz")
     */
    private $quizID;

    /**
     * @ORM\OneToMany(targetEntity=Answers::class, mappedBy="questionId",  cascade={"persist"})
     * @Assert\Count(
     *     min = 2,
     *     max = 6
     * )
     */
    private $answers;

    public function __construct()
    {
        $this->quizID = new ArrayCollection();
        $this->answers = new ArrayCollection();
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

    /**
     * @return Collection|Answers[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answers $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestionId($this);
        }

        return $this;
    }

    public function removeAnswer(Answers $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestionId() === $this) {
                $answer->setQuestionId(null);
            }
        }

        return $this;
    }

}
