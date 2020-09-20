<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Form\QuestionForm;
use App\Form\QuizForm;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
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

        // Создать ArrayCollection текущих объектов Answer в DB
        foreach ($question->getAnswers() as $answer) {
            $originalAnswers->add($answer);
        }
        $form = $this->createForm(QuestionForm::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {

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
