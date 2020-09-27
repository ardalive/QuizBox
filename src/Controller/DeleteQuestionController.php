<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Entity\Quiz;
use App\Form\QuestionForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeleteQuestionController extends AbstractController
{
    /**
     * @Route("/admin/question/delete{id}", name="delete_question")
     */
    public function index(int $id, EntityManagerInterface $entityManager)
    {
        $oneQuestion = $entityManager->getRepository(Questions::class)->find($id);

        if (!$oneQuestion) {
            throw $this->createNotFoundException(
                'This quiz does not exists'
            );
        }

        $entityManager->remove($oneQuestion);
        $entityManager->flush();

        return $this->redirectToRoute('question_page');
    }
}
