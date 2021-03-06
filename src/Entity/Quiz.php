<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, minMessage = "This value is too short. The name of the quiz should have 2 characters or more.")
     * @Assert\Length(max=50, maxMessage = "This value is too lond. The name of the quiz should have 50 characters or less.")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min=3, minMessage = "This value is too short. Description of the quiz should have 3 characters or more.")
     * @Assert\Length(max=350, maxMessage = "This value is too long. Description of the quiz should have 350 characters or less.")
     */
    private $Description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="date")
     *
     */
    private $dateOfCreation;

    /**
     * @ORM\ManyToMany(targetEntity=Questions::class, mappedBy="quizID", cascade={"persist"})
     * @Assert\Count(
     *     min = 5,
     *     minMessage="The number of questions must be more than 5",
     *     max = 60,
     *     maxMessage="The number of questions must be less than 60"
     * )
     */
    private $questionID;

    public function __construct()
    {
        $this->questionID = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getDateOfCreation(): ?\DateTimeInterface
    {
        return $this->dateOfCreation;
    }

    public function setDateOfCreation(\DateTimeInterface $dateOfCreation): self
    {
        $this->dateOfCreation = $dateOfCreation;

        return $this;
    }

    /**
     * @return Collection|Questions[]
     */
    public function getQuestionID(): Collection
    {
        return $this->questionID;
    }

    public function addQuestionID(Questions $questionID): self
    {
        if (!$this->questionID->contains($questionID)) {
            $this->questionID[] = $questionID;
            $questionID->addQuizID($this);
        }

        return $this;
    }

    public function removeQuestionID(Questions $questionID): self
    {
        if ($this->questionID->contains($questionID)) {
            $this->questionID->removeElement($questionID);
            $questionID->removeQuizID($this);
        }

        return $this;
    }

}
