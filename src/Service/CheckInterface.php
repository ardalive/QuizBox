<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\PlayerAnswers;
use App\Entity\Questions;
use App\Repository\AnswersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckInterface extends AbstractController
{
    public function checkParams(array $params) :array
    {
        $pattern = '/\d+/';
        $cleanedParams = [];
        foreach ($params as $parameter){
            array_push($cleanedParams, (int)preg_filter($pattern, "", $parameter));
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
        return array_search($paramsArray['quest_id'], array_keys($answers));
    }
    public function answerIsCorrect(array $paramsArray, AnswersRepository $answersRepository) :bool
    {
        $answer = $answersRepository->findOneBy(['id'=>$paramsArray['ans_id']]);
        return $answer->getIsTrue();
    }
    public function updatePlayerAnswer(PlayerAnswers $playerAnswers):void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($playerAnswers);
        $entityManager->flush();
    }
    public function setAnswer(array $paramsArray, PlayerAnswers $playerAnswers):void
    {
        $answers[$paramsArray['quest_id']] = $paramsArray['ans_id'];
        $playerAnswers->setAnswers($answers);
        $this->updatePlayerAnswer($playerAnswers);
    }
}