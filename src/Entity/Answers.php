<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswersRepository::class)
 */
class Answers
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
    private $AnswerBody;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTrue;

    /**
     * @ORM\ManyToOne(targetEntity=Questions::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $questionID;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswerBody(): ?string
    {
        return $this->AnswerBody;
    }

    public function setAnswerBody(string $AnswerBody): self
    {
        $this->AnswerBody = $AnswerBody;

        return $this;
    }

    public function getIsTrue(): ?bool
    {
        return $this->isTrue;
    }

    public function setIsTrue(bool $isTrue): self
    {
        $this->isTrue = $isTrue;

        return $this;
    }

    public function getQuestionID(): ?Questions
    {
        return $this->questionID;
    }

    public function setQuestionID(?Questions $questionID): self
    {
        $this->questionID = $questionID;

        return $this;
    }
}
