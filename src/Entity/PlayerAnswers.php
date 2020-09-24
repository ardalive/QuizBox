<?php

namespace App\Entity;

use App\Repository\PlayerAnswersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerAnswersRepository::class)
 */
class PlayerAnswers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="playerAnswers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userRelation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $quizRelation;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $answers = [];

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $timeToSolve;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserRelation(): ?User
    {
        return $this->userRelation;
    }

    public function setUserRelation(?User $userRelation): self
    {
        $this->userRelation = $userRelation;

        return $this;
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

    public function getQuizRelation(): ?Quiz
    {
        return $this->quizRelation;
    }

    public function setQuizRelation(?Quiz $quizRelation): self
    {
        $this->quizRelation = $quizRelation;

        return $this;
    }

    public function getAnswers(): ?array
    {
        return $this->answers;
    }

    public function setAnswers(?array $answers): self
    {
        $this->answers = $answers;

        return $this;
    }

    public function getTimeToSolve(): ?\DateInterval
    {
        return $this->timeToSolve;
    }

    public function setTimeToSolve(?\DateInterval $timeToSolve): self
    {
        $this->timeToSolve = $timeToSolve;

        return $this;
    }
}
