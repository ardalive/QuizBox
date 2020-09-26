<?php
declare(strict_types=1);

namespace App\Service;


class QuizInterface
{
    public function bindQuizWithQuestions($quiz, $questions): self
    {
        foreach($questions as $question){
            $quiz->addQuestionID($question);
            $question->addQuizID($quiz);
        }
        return $this;
    }

    public function deleteOldQuestions($quiz, $oldQuestions) :self
    {
        foreach($oldQuestions as $val){
            $val->removeQuizID($quiz);
        }
        return $this;
    }
}