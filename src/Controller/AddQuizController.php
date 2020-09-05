<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddQuizController extends AbstractController
{
    /**
     * @Route("/admin/quizadd", name="add_quiz")
     */
    public function index(Request $request)
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizForm::class, $quiz, ['attr'=>['class'=>'addQuizForm']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quiz->setDateOfCreation(new \DateTime('today'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('add_quiz/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
