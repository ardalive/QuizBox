<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EditQuizController extends AbstractController
{
    /**
     * @Route("admin/quiz/edit/{id}", name="edit_quiz", requirements={"id"="\d+"})
     */
    public function index(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($id);

        $form = $this->createForm(QuizForm::class, $quiz, ['entityManager' => $this->getDoctrine()->getManager()]);

        $form->handleRequest($request);

        $a = $form->get('questionID')->getData();

        if ($form->isSubmitted() ) {
            foreach($a as $val){
                $quiz->addQuestionID($val);
                $val->addQuizID($quiz);
            }
            $quiz = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('admin');

        }

        return $this->render('edit_quiz/index.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }
}
