<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Entity\Quiz;
use App\Form\QuizForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddQuizController extends AbstractController
{
    /**
     * @Route("/admin/quizadd", name="add_quiz")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizForm::class, $quiz, ['attr'=>['class'=>'addQuizForm']]);
        $repository = $entityManager->getRepository(Questions::class);
        $questionsArr = $repository->findAll();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quiz->setDateOfCreation(new \DateTime('today'));

            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('add_quiz/index.html.twig', [
            'form' => $form->createView(),
            'questionsArr' => $questionsArr,
        ]);
    }
}
