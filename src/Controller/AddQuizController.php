<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Entity\Quiz;
use App\Form\QuizForm;
use App\Service\choicegenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddQuizController extends AbstractController
{
    /**
     * @Route("/admin/quiz/quizadd", name="add_quiz")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizForm::class, $quiz, ['entityManager' => $this->getDoctrine()->getManager()]);
        $form->handleRequest($request);
        $a = $form->get('questionID')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            foreach($a as $val){
                $quiz->addQuestionID($val);
                $val->addQuizID($quiz);
            }
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
