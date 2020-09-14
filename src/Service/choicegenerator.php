<?php


namespace App\Service;


use App\Entity\Questions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class choicegenerator extends AbstractController
{
    public function buildChoices() {
        $choices = [];
        $questionsRepository = $this->getDoctrine()->getRepository(Questions::class);
        $allQuestions = $questionsRepository->findAll();

        foreach ($allQuestions as $oneQuestion) {
            $choices[$oneQuestion->getId()] =  $oneQuestion->getQuestionBody();
        }

        return $choices;
    }
}