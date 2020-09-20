<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Entity\Quiz;
use App\Form\QuizForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EditQuizController extends AbstractController
{
    /**
     * @Route("admin/quiz/edit{id}", name="edit_quiz", requirements={"id"="\d+"})
     */
    public function index(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($id);
        $oldQuestions = $quiz->getQuestionID();
        foreach($oldQuestions as $val){
            $val->removeQuizID($quiz);
        }
        $form = $this->createForm(QuizForm::class, $quiz, ['entityManager' => $this->getDoctrine()->getManager()]);

        $form->handleRequest($request);

        $newQuestions = $form->get('questionID')->getData();


        if ($form->isSubmitted()) {

            foreach($newQuestions as $val){
                $quiz->addQuestionID($val);
                $val->addQuizID($quiz);
            }

            $quiz = $form->getData();

            $entityManager->flush();

            return $this->redirectToRoute('quiz_page');

        }

        return $this->render('edit_quiz/index.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }
}
