<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Form\QuestionForm;
use App\Form\QuizForm;
use Doctrine\Common\Collections\ArrayCollection;
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
    public function index(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $question = $entityManager->getRepository(Questions::class)->find($id);

        $originalAnswers = new ArrayCollection();

        foreach ($question->getAnswers() as $answer) {
            $originalAnswers->add($answer);
        }
        $form = $this->createForm(QuestionForm::class, $question);
        $form->handleRequest($request);

        $allAnswers = $question->getAnswers();
        $counter = 0;
        foreach ($allAnswers as $answer){
            if($answer->getIsTrue() == true){
                $counter++;
            }
        }
        if($counter != 1 && $form->isSubmitted()){
            $form->addError(new FormError('there must be 1 correct answer'));
        }

        if ($form->isSubmitted() && $form->isValid() && $counter === 1) {

            foreach ($originalAnswers as $answer) {
                if (false === $question->getAnswers()->contains($answer)) {
                    $entityManager->remove($answer);
                }
            }

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
