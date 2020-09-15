<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     * @Assert\Length(max=100, min=1)
     */
    private $AnswerBody;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTrue;


    /**
     * @ORM\ManyToOne(targetEntity=Questions::class, inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $questionId;

    public function getQuestionId(): ?Questions
    {
        return $this->questionId;
    }

    public function setQuestionId(?Questions $questions): self
    {
        $this->questionId = $questions;

        return $this;
    }

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

}
