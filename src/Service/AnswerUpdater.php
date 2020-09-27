<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\PlayerAnswers;
use App\Repository\AnswersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnswerUpdater extends AbstractController
{
    private $answersRepository;

    public function __construct(AnswersRepository $answersRepository){
        $this->answersRepository = $answersRepository;
    }
    public function checkParams(array $paramsArray) :array
    {
        $cleanedParams = [];
        foreach ($paramsArray as $key => $value){
            $cleanedParams[$key] = intval($value);
        }
        return $cleanedParams;
    }
    public function getQuestionIDsArray(array $questions) :array
    {
        $questionsIDsArray = [];
        foreach ($questions as $question) {
            array_push( $questionsIDsArray, $question->getId());
        }
        return $questionsIDsArray;
    }
    public function questionBelongsToQuiz(array $paramsArray, array $questionsIDsArray) :bool
    {
        if(array_search($paramsArray['quest_id'], $questionsIDsArray) !== FALSE){
            return true;
        }
        else return false;
    }
    public function answeredBefore(array $paramsArray, array $answers):bool
    {
        return (bool)array_search($paramsArray['quest_id'], array_keys($answers));
    }
    public function answerIsCorrect(array $paramsArray) :bool
    {
        $answer = $this->answersRepository->findOneBy(['id'=>$paramsArray['ans_id']]);
        return $answer->getIsTrue();
    }
    public function updatePlayerAnswer(PlayerAnswers $playerAnswers):void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($playerAnswers);
        $entityManager->flush();
    }
    public function setAnswer(array $paramsArray, array $answers, PlayerAnswers $playerAnswers, bool $isCorrect):void
    {
        $answers[$paramsArray['quest_id']] = $paramsArray['ans_id'];
        if($isCorrect)
        {
            $playerAnswers->setCorrectAnswers($this->incrementCorrectAnswers($playerAnswers));
        }
        $playerAnswers->setAnswers($answers);
        $this->updatePlayerAnswer($playerAnswers);
    }
    public function incrementCorrectAnswers(PlayerAnswers $playerAnswers):int
    {
        return $playerAnswers->getCorrectAnswers() + 1;
    }
}