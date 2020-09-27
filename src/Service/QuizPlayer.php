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


class QuizPlayer extends AbstractController
{
    private $playerAnswersRepository;
    private $answersRepository;
    private $questionsRepository;

    public function __construct(AnswersRepository $answersRepository, PlayerAnswersRepository $playerAnswersRepository, QuestionsRepository $questionsRepository)
    {
        $this->answersRepository = $answersRepository;
        $this->playerAnswersRepository = $playerAnswersRepository;
        $this->questionsRepository = $questionsRepository;
    }
    public function getPlayerAnswers(User $player, Quiz $quiz, \DateTime $date) :PlayerAnswers
    {
        if(!$playerAnswers = $this->playerAnswersRepository->findByUserAndQuiz($player, $quiz)){
            $this->initializePlayerAnswers($player, $quiz, $date);
            return $this->playerAnswersRepository->findByUserAndQuiz($player, $quiz);
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
        return $playerAnswers->getTimeToSolve() === NULL;
    }
    public function updatePlayerAnswers(PlayerAnswers $playerAnswers):void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($playerAnswers);
        $entityManager->flush();
    }
    public function getAllCorrectAnswerIDs():array
    {
        $correctAnswerIDs = [];
        foreach ($this->answersRepository->findBy(['isTrue'=>1]) as $item){
            array_push($correctAnswerIDs, $item->getId());
        }
        return $correctAnswerIDs;
    }
    public function getAmountOfCorrectAnswers(PlayerAnswers $playerAnswers):int
    {
        $amountOfCorrectAnswers = 0;
        foreach ($playerAnswers->getAnswers() as $answer){
            if(array_search($answer, $this->getAllCorrectAnswerIDs())) {
                $amountOfCorrectAnswers+=1;
            }
        }
        return $amountOfCorrectAnswers;
    }
    public function getNextQuestion(array $unansweredQuestions) :Questions
    {
        return $this->questionsRepository->findOneBy([
            'id' => array_values($unansweredQuestions)[0]
        ]);
    }
    public function getQuestionNumber(array $unansweredQuestions, array $questionsIDsArray): int
    {
        return array_search(array_values($unansweredQuestions)[0], $questionsIDsArray) + 1;
    }

    public function finishQuiz(PlayerAnswers $playerAnswers, \DateTime $date)
    {
        $playerAnswers->setTimeToSolve($playerAnswers->getStartDate()->diff($date));
//        $amountOfCorrectAnswers = $this->getAmountOfCorrectAnswers($playerAnswers);
//        $playerAnswers->setCorrectAnswers($amountOfCorrectAnswers);
        $this->updatePlayerAnswers($playerAnswers);
    }
}