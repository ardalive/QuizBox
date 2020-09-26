<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Form\QuestionForm;
use App\Service\QuestionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddQuestionController extends AbstractController
{
    /**
     * @Route("/admin/quiz/questionadd", name="add_question")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, QuestionInterface $questionInterface)
    {
        $question = new Questions();
        $form = $this->createForm(QuestionForm::class, $question);
        $form->handleRequest($request);

        $correctAnswers = $questionInterface->countCorrectAnswers($question->getAnswers());
        if($correctAnswers != 1 && $form->isSubmitted()){
             $form->addError(new FormError('there must be 1 correct answer'));
        }

        if ($form->isSubmitted() && $form->isValid() && $correctAnswers === 1  ) {
            $entityManager->persist($question);
            $entityManager->flush();
            return $this->redirectToRoute('add_question');
        }

        return $this->render('add_question/addQuestion.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}