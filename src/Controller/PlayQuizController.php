<?php
declare(strict_types=1);
namespace App\Controller;


use App\Repository\PlayerAnswersRepository;
use App\Repository\QuestionsRepository;
use App\Repository\QuizRepository;
use App\Service\AnswerUpdater;
use App\Service\QuizPlayer;
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
    public function check(Request $request, QuestionsRepository $questionsRepository, PlayerAnswersRepository $playerAnswersRepository, UserInterface $user, AnswerUpdater $updater) :Response
    {
        $paramsArray = $updater->checkParams($request->request->all());

        $questions = $questionsRepository->findQuestionsByQuizID($paramsArray['quiz_id']);
        $questionsIDsArray = $updater->getQuestionIDsArray($questions);

        if($updater->questionBelongsToQuiz($paramsArray, $questionsIDsArray)){
            $paramsArray['user_id'] = $user->getUser()->getId();
            $playerAnswers = $playerAnswersRepository->findByUserQuizId($paramsArray);
            $answers = $playerAnswers->getAnswers();

            if($updater->answeredBefore($paramsArray, $answers)){
                $response = ['error'=>'You have already answered this question'];
            }
            else{
                $isCorrect = $updater->answerIsCorrect($paramsArray);
                $updater->setAnswer($paramsArray, $answers, $playerAnswers, $isCorrect);
                $response = $isCorrect;
            }
        } else{
            $response = ['error'=>'Question does not belongs to the quiz'];
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/play/{quizID}", name="play_quiz")
     */
    public function playQuiz(int $quizID, QuizRepository $quizRepository, QuestionsRepository $questionsRepository, UserInterface $user, QuizPlayer $quizPlayer) :Response
    {
        $date = new DateTime();
        $player = $user->getUser();
        $quiz = $quizRepository->findOneBy(['id'=>$quizID]);
        $questions = $questionsRepository->findQuestionsByQuizID($quizID);

        $playerAnswers = $quizPlayer->getPlayerAnswers($player, $quiz, $date);
        $questionsIDsArray = $quizPlayer->getQuestionsIDs($questions);
        $unansweredQuestions = $quizPlayer->getUnansweredQuestions($questionsIDsArray, $playerAnswers);

        if(count($unansweredQuestions)>0){
            $questionNumber = $quizPlayer->getQuestionNumber($unansweredQuestions, $questionsIDsArray);
            return $this->render('play_quiz/play_quiz.html.twig', [
                'question'=>$quizPlayer->getNextQuestion($unansweredQuestions),
                'page'=> $questionNumber,
                'amountOfQuestions' => count($questions)
            ]);
        }
        else{
            if($quizPlayer->quizSolved($playerAnswers)){
                $quizPlayer->finishQuiz($playerAnswers, $date);
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
