<?php
declare(strict_types=1);

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;

class QuestionInterface
{
    public function getPreviousAnswers($question) : ArrayCollection
    {
        $originalAnswers = new ArrayCollection();
        foreach ($question->getAnswers() as $answer) {
            $originalAnswers->add($answer);
        }
        return $originalAnswers;
    }

    public function removePreviousAnswers($question, $originalAnswers, $entityManager): self
    {
        foreach ($originalAnswers as $answer) {
            if ($question->getAnswers()->contains($answer) === false) {
                $entityManager->remove($answer);
            }
        }
        return $this;
    }

    public function countCorrectAnswers($allAnswers): int
    {
        $counter = 0;
        foreach ($allAnswers as $answer){
            if($answer->getIsTrue() == true){
                $counter++;
            }
        }
        return $counter;
    }
}