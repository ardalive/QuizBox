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
    public function check(Request $request, QuestionsRepository $questionsRepository, UserRepository $userRepository, PlayerAnswersRepository $playerAnswersRepository, AnswersRepository $answersRepository, UserInterface $user) :Response
    {
        //check if POST params contain ID`s only (decimal values), filter everything else, push params into paramsArray
        $paramsArray = $request->request->all();
        $pattern = '/\d+/';
        foreach ($paramsArray as $item){
            $item = preg_filter($pattern, "", $item);
        }

        // !! similar block in playQuiz controller !!
        // get array with question ID`s for passed quiz ID
        $questions = $questionsRepository->findQuestionsByQuizID((int)$paramsArray['quiz_id']);
        $questionsArray = [];
        foreach ($questions as $question) {
            array_push( $questionsArray, $question->getId());
        }


        // if passed question belongs to passed quiz - proceed, else return error message
        if(array_search($paramsArray['quest_id'], $questionsArray) !== FALSE){

            $paramsArray['user_id'] = $userRepository->findOneBy(['email'=>$user->getUsername()])->getId();


            //get player answers for logged user for current quiz (quiz_id passed in params array)
            $playerAnswers = $playerAnswersRepository->findByUserQuizId($paramsArray);


            //get passed answer and check if it`s correct
            $answer = $answersRepository->findOneBy(['id'=>$request->request->get('ans_id')]);
            $isCorrect = $answer->getIsTrue();


            $entityManager = $this->getDoctrine()->getManager();
            $answers = $playerAnswers->getAnswers();

            // if there is no answer for passed question - push new answer into DB, else return error message
            if(!array_search($request->request->get('quest_id'), array_keys($answers))){
                $answers[$request->request->get('quest_id')] = $request->request->get('ans_id');
                $playerAnswers->setAnswers($answers);
                $entityManager->persist($playerAnswers);
                $entityManager->flush();

                $response = $isCorrect;
            } else{
                $response = ['error'=>'No cheating! You have already answered this question :D'];
            }

        } else{
//            $response = ['error'=>'Question does not belongs to the quiz. Cheater!'];
            $response = [$paramsArray['quest_id'], $questionsArray];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/play/{quizID}", name="play_quiz")
     */
    public function playQuiz(int $quizID, UserRepository $userRepository, PlayerAnswersRepository $playerAnswersRepository, QuizRepository $quizRepository, QuestionsRepository $questionsRepository, UserInterface $user) :Response
    {
        $date = new DateTime();
        $entityManager = $this->getDoctrine()->getManager();

        // setting parameters array
        $userIdQuizId = [
            'user_id' => $userRepository->findOneBy(['email'=>$user->getUsername()])->getId(),
            'quiz_id' => $quizID
        ];

        // get player answers by user ID and quiz ID
        $playerAnswers = $playerAnswersRepository->findByUserQuizId($userIdQuizId);

        // if user joins quiz for the first time - push new playerAnswers log into DB
        if($playerAnswers === NULL){
            $playerAnswers = new PlayerAnswers();
            $playerAnswers->setStartDate($date);
            $playerAnswers->setQuizRelation($quizRepository->findOneBy(['id'=>$quizID]));
            $playerAnswers->setUserRelation($userRepository->findOneBy(['email'=>$user->getUsername()]));
            $entityManager->persist($playerAnswers);
            $entityManager->flush();
        }


        $quiz = $quizRepository->findOneBy(['id'=>$quizID]);

        // !! similar block in Check controller !!
        // get array with question ID`s for passed quiz ID,
        $questions = $questionsRepository->findQuestionsByQuizID($quizID);
        $questionsArray = [];
        foreach ($questions as $question) {
            array_push( $questionsArray, $question->getId());
        }
        //

        // get difference between (question ID`s in passed quiz) and (question ID`s in playerAnswers in DB)
        $difference = array_diff($questionsArray, array_keys($playerAnswersRepository->findByUserQuizId($userIdQuizId)->getAnswers()));

        // if there are questions without answers, return question page Response, if all the questions are answered - goto Champions route
        if(count($difference)>0){
            $pageNumber = array_search(array_values($difference)[0], $questionsArray) + 1;
            return $this->render('play_quiz/play_quiz.html.twig', [
                'questions'=>$questions,
                'questionNumber'=>$pageNumber,
                'user'=>$quiz,
            ]);
        }
        else{
            return new RedirectResponse('/champions');
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
