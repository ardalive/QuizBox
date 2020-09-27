<?php
declare(strict_types=1);

namespace App\Service;


use App\Entity\PlayerAnswers;
use App\Entity\Questions;
use App\Entity\Quiz;
use App\Entity\User;
use App\Repository\AnswersRepository;
use App\Repository\PlayerAnswersRepository;
use App\Repository\QuestionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PlayInterface extends AbstractController
{

    public function getPlayerAnswers(User $player, Quiz $quiz, \DateTime $date, PlayerAnswersRepository $playerAnswersRepository) :PlayerAnswers
    {
        if(!$playerAnswers = $playerAnswersRepository->findByUserAndQuiz($player, $quiz)){
            $this->initializePlayerAnswers($player, $quiz, $date);
            return $playerAnswersRepository->findByUserAndQuiz($player, $quiz);
        }
        else return $playerAnswers;
    }

    public function initializePlayerAnswers(User $user, Quiz $quiz, \DateTime $date) :void
    {
        $playerAnswers = new PlayerAnswers();
        $playerAnswers->setStartDate($date);
        $playerAnswers->setQuizRelation($quiz);
        $playerAnswers->setUserRelation($user);
        $this->updatePlayerAnswers($playerAnswers);
    }
    public function getQuestionsIDs(array $questions):array
    {
        $questionsIDsArray = [];
        foreach ($questions as $question) {
            array_push( $questionsIDsArray, $question->getId());
        }
        return $questionsIDsArray;
    }
    public function getUnansweredQuestions(array $questionsIDsArray, PlayerAnswers $playerAnswers):array
    {
        return array_diff($questionsIDsArray, array_keys($playerAnswers->getAnswers()));
    }
    public function quizSolved(PlayerAnswers $playerAnswers):bool
    {
        return $playerAnswers->getCorrectAnswers() === NULL;
    }
    public function updatePlayerAnswers(PlayerAnswers $playerAnswers):void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($playerAnswers);
        $entityManager->flush();
    }
    public function getAllCorrectAnswerIDs(AnswersRepository $answersRepository):array
    {
        $correctAnswerIDs = [];
        foreach ($answersRepository->findBy(['isTrue'=>1]) as $item){
            array_push($correctAnswerIDs, $item->getId());
        }
        return $correctAnswerIDs;
    }
    public function getAmountOfCorrectAnswers(PlayerAnswers $playerAnswers, AnswersRepository $answersRepository):int
    {
        $amountOfCorrectAnswers = 0;
        foreach ($playerAnswers->getAnswers() as $answer){
            if(array_search($answer, $this->getAllCorrectAnswerIDs($answersRepository))) {
                $amountOfCorrectAnswers+=1;
            }
        }
        return $amountOfCorrectAnswers;
    }
    public function getNextQuestion(array $unansweredQuestions, QuestionsRepository $questionsRepository) :Questions
    {
        return $questionsRepository->findOneBy([
            'id' => array_values($unansweredQuestions)[0]
        ]);
    }
    public function getQuestionNumber(array $unansweredQuestions, array $questionsIDsArray): int
    {
        return array_search(array_values($unansweredQuestions)[0], $questionsIDsArray) + 1;
    }

    public function finishQuiz(PlayerAnswers $playerAnswers, \DateTime $date, AnswersRepository $answersRepository)
    {
        $playerAnswers->setTimeToSolve($playerAnswers->getStartDate()->diff($date));
        $amountOfCorrectAnswers = $this->getAmountOfCorrectAnswers($playerAnswers, $answersRepository);
        $playerAnswers->setCorrectAnswers($amountOfCorrectAnswers);
        $this->updatePlayerAnswers($playerAnswers);
    }
}