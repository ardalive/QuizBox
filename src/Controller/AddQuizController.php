<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizForm;
use App\Service\QuizInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddQuizController extends AbstractController
{
    /**
     * @Route("/admin/quiz/quizadd", name="add_quiz")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, QuizInterface $quizInterface)
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizForm::class, $quiz, ['entityManager' => $this->getDoctrine()->getManager()]);
        $form->handleRequest($request);
        $questions = $form->get('questionID')->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            $quizInterface->bindQuizWithQuestions($quiz, $questions);
            $quiz->setDateOfCreation(new \DateTime('today'));
            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('add_quiz/addQuiz.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}
