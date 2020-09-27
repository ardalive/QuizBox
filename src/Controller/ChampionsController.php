<?php

namespace App\Controller;

use App\Repository\AnswersRepository;
use App\Repository\PlayerAnswersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChampionsController extends AbstractController
{
    /**
     * @Route("/{_locale<%app.supported_locales%>}/champions/{quizID}", name="champions")
     */
    public function index(int $quizID, PlayerAnswersRepository $playerAnswersRepository)
    {
        $answers = $playerAnswersRepository->findLeadersInQuiz($quizID);
        $timeDiff = [];
        foreach ($answers as $answer){
            array_push($timeDiff, $answer->getTimeToSolve()->format('%h:%i:%s'));
        }

        return $this->render('champions/champions.html.twig', [
            'champs' => $answers,
            'times' => $timeDiff
        ]);
    }
}
