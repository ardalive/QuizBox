<?php
declare(strict_types=1);
namespace App\Controller;


use App\Repository\AnswersRepository;
use App\Repository\PlayerAnswersRepository;
use App\Repository\QuestionsRepository;
use App\Repository\QuizRepository;
use App\Service\CheckInterface;
use App\Service\PlayInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class PlayQuizController extends AbstractController
{
    /**
     * @Route("/check", name="check", methods={"POST"})
     */
    public function check(Request $request, QuestionsRepository $questionsRepository, PlayerAnswersRepository $playerAnswersRepository, AnswersRepository $answersRepository, UserInterface $user, CheckInterface $check) :Response
    {
        $paramsArray = $check->checkParams($request->request->all());
        $questions = $questionsRepository->findQuestionsByQuizID($paramsArray['quiz_id']);
        $questionsIDsArray = $check->getQuestionIDsArray($questions);

        if($check->questionBelongsToQuiz($paramsArray, $questionsIDsArray)){
            $paramsArray['user_id'] = $user->getUser()->getId();
            $playerAnswers = $playerAnswersRepository->findByUserQuizId($paramsArray);
            $answers = $playerAnswers->getAnswers();

            if($check->answeredBefore($paramsArray, $answers)){
                $response = ['error'=>'No cheating! You have already answered this question :D'];
            }
            else{
                $check->setAnswer($paramsArray, $playerAnswers);
                $response = $check->answerIsCorrect($paramsArray, $answersRepository);
            }
        } else{
            $response = ['error'=>'Question does not belongs to the quiz. Cheater!'];
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/play/{quizID}", name="play_quiz")
     */
    public function playQuiz(int $quizID, AnswersRepository $answersRepository, PlayerAnswersRepository $playerAnswersRepository, QuizRepository $quizRepository, QuestionsRepository $questionsRepository, UserInterface $user, PlayInterface $play) :Response
    {
        $date = new DateTime();
        $player = $user->getUser();
        $quiz = $quizRepository->findOneBy(['id'=>$quizID]);
        $questions = $questionsRepository->findQuestionsByQuizID($quizID);

        $playerAnswers = $play->getPlayerAnswers($player, $quiz, $date, $playerAnswersRepository);
        $questionsIDsArray = $play->getQuestionsIDs($questions);
        $unansweredQuestions = $play->getUnansweredQuestions($questionsIDsArray, $playerAnswers);

        if(count($unansweredQuestions)>0){
            $questionNumber = $play->getQuestionNumber($unansweredQuestions, $questionsIDsArray);
            return $this->render('play_quiz/play_quiz.html.twig', [
                'question'=>$play->getNextQuestion($unansweredQuestions, $questionsRepository),
                'page'=> $questionNumber,
                'amountOfQuestions' => count($questions)
            ]);
        }
        else{
            if($play->quizSolved($playerAnswers)){
                $play->finishQuiz($playerAnswers, $date, $answersRepository);
            }
            return new RedirectResponse('/champions/'.$quizID);
        }
    }


    /**
     * @Route("/play/", name="play")
     */
    public function index() :Response
    {
        return $this->redirectToRoute('homepage');
    }
}
