<?php

namespace App\Controller;

use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeleteQuizController extends AbstractController
{
    /**
     * @Route("admin/quiz/delete{id}", name="delete_quiz")
     */
    public function index(int $id, EntityManagerInterface $entityManager)
    {
        $oneQuiz = $entityManager->getRepository(Quiz::class)->find($id);

        if (!$oneQuiz) {
            throw $this->createNotFoundException(
                'This quiz does not exists'
            );
        }

        $entityManager->remove($oneQuiz);
        $entityManager->flush();

        return $this->render('delete_quiz/index.html.twig', [
            'controller_name' => 'DeleteQuizController',
            'id' => $id,
        ]);
    }
}
