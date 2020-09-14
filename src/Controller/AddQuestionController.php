<?php


namespace App\Controller;



use App\Entity\Answers;
use App\Entity\Questions;
use App\Form\QuestionForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddQuestionController extends AbstractController
{
    /**
     * @Route("/admin/questionadd", name="add_question")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $question = new Questions();

        $form = $this->createForm(QuestionForm::class, $question);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('add_question');
        }

        return $this->render('add_question/addQuestion.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}