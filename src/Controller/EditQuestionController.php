<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Form\QuestionForm;
use App\Service\QuestionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EditQuestionController extends AbstractController
{
    /**
     * @Route("/admin/question/edit{id}", name="edit_question")
     */
    public function index(int $id, EntityManagerInterface $entityManager, Request $request, QuestionInterface $questionInterface)
    {
        $question = $entityManager->getRepository(Questions::class)->find($id);
        $originalAnswers = $questionInterface->getPreviousAnswers($question);
        $form = $this->createForm(QuestionForm::class, $question);
        $form->handleRequest($request);

        $correctAnswersCount = $questionInterface->countCorrectAnswers($question->getAnswers());
        if($correctAnswersCount != 1 && $form->isSubmitted()){
            $form->addError(new FormError('there must be 1 correct answer'));
        }

        if ($form->isSubmitted() && $form->isValid() && $correctAnswersCount === 1) {
            $questionInterface->removePreviousAnswers($question, $originalAnswers, $entityManager);
            $entityManager->persist($question);
            $entityManager->flush();
            return $this->redirectToRoute('question_page');
        }

        return $this->render('edit_question/index.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }
}
