<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Answers;
use App\Entity\PlayerAnswers;
use App\Entity\Questions;
use App\Entity\Quiz;
use App\Repository\AnswersRepository;
use App\Repository\PlayerAnswersRepository;
use App\Repository\QuestionsRepository;
use App\Repository\QuizRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class PlayQuizController extends AbstractController
{
    /**
     * @Route("/check", name="check", methods={"POST"})
     */
    public function check(Request $request, QuizRepository $quizRepository, UserRepository $userRepository, PlayerAnswersRepository $playerAnswersRepository, AnswersRepository $answersRepository, UserInterface $user) :Response
    {
//        if($request->isXmlHttpRequest()){        }
            $playerAnswers = $playerAnswersRepository->findByUserQuizId($request->request->all());
            $date = new DateTime();
            $answer = $answersRepository->findOneBy(['id'=>$request->request->get('ans_id')]);
            $iscorrect = $answer->getIsTrue();
            $entityManager = $this->getDoctrine()->getManager();
            if(!$playerAnswers->getId()){
                $playerAnswers = new PlayerAnswers();
                $playerAnswers->setStartDate($date);
                $playerAnswers->setQuizRelation($quizRepository->findOneBy(['id'=>$request->request->get('quiz_id')]));
                $playerAnswers->setUserRelation($userRepository->findOneBy(['email'=>$user->getUsername()]));
                $playerAnswers->setAnswers([$request->request->get('quest_id')=>$request->request->get('ans_id')]);
            }
            else {
                $answers = $playerAnswers->getAnswers();
                $answers[$request->request->get('quest_id')] = $request->request->get('ans_id');
                $playerAnswers->setAnswers($answers);
            }

        $entityManager->persist($playerAnswers);
        $entityManager->flush();
        return new JsonResponse($iscorrect);

    }

    /**
     * @Route("/play/{quizID}/{questionNumber}", name="play_quiz")
     */
    public function playQuiz(int $quizID, int $questionNumber, QuizRepository $quizRepository, QuestionsRepository $questionsRepository, PlayerAnswersRepository $playerAnswersRepository, UserInterface $user) :Response
    {
        $quiz = $quizRepository->findOneBy(['id'=>$quizID]);
//        $questions = $questionsRepository->find();
//        $playerAnswers = $playerAnswersRepository->findOneBy();
        $questions = $questionsRepository->findQuestionsByQuizID($quizID);
        return $this->render('play_quiz/play_quiz.html.twig', [
            'questions'=>$questions,
            'questionNumber'=>$questionNumber,
            'user'=>$quiz
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
