<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Entity\Quiz;
use App\Form\QuizForm;
use App\Service\QuizInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EditQuizController extends AbstractController
{
    /**
     * @Route("admin/quiz/edit{id}", name="edit_quiz", requirements={"id"="\d+"})
     */
    public function index(int $id, EntityManagerInterface $entityManager, Request $request, QuizInterface $quizInterface)
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($id);
        $form = $this->createForm(QuizForm::class, $quiz, ['entityManager' => $this->getDoctrine()->getManager()]);
        $quizInterface->deleteOldQuestions($quiz, $quiz->getQuestionID());


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quizInterface->bindQuizWithQuestions($quiz, $form->get('questionID')->getData());
            $entityManager->flush();
            return $this->redirectToRoute('quiz_page');

        }

        return $this->render('edit_quiz/index.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }
}
