<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Questions;
use App\Entity\Quiz;
use App\Repository\AnswersRepository;
use App\Repository\QuestionsRepository;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayQuizController extends AbstractController
{
    /**
     * @Route("/check", name="check", methods={"POST"})
     */
    public function check(Request $request, AnswersRepository $answersRepository) :Response
    {
//        $request->request->get('id')
        if($request->isXmlHttpRequest()){
            $answer = $answersRepository->findOneBy(['id'=>$request->request->get('id')]);
            $jsonResponse = $answer->getIsTrue();
        }
        return new JsonResponse($jsonResponse);
//        return $this->redirectToRoute('homepage', $jsonData);
    }

    /**
     * @Route("/play/{quizID}/{questionNumber}", name="play_quiz")
     */
    public function playQuiz(int $quizID, int $questionNumber, QuestionsRepository $questionsRepository, Request $request) :Response
    {
        $questions = $questionsRepository->findQuestionsByQuizID($quizID);
        return $this->render('play_quiz/play_quiz.html.twig', [
            'questions'=>$questions,
            'questionNumber'=>$questionNumber
        ]);
    }

    /**
     * @Route("/play/{quizID}", name="quiz")
     */
    public function quiz(int $quizID, QuestionsRepository $questionsRepository) :Response
    {
//        $questions = $questionsRepository->findQuestionsByQuizID($quizID);
        return $this->redirectToRoute('play_quiz', ['quizID'=>$quizID, 'questionNumber'=>1]);
    }

    /**
     * @Route("/play/", name="play")
     */
    public function index() :Response
    {
        return $this->redirectToRoute('homepage');
    }
}
