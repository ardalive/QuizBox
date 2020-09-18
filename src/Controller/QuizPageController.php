<?php

namespace App\Controller;

use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuizPageController extends AbstractController
{
    /**
     * @Route("/admin/quiz", name="quiz_page")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $allQuiz = $entityManager->getRepository(Quiz::class)->findAll();
        if (!$allQuiz) {
            throw $this->createNotFoundException(
                'Not found :('
            );
        }

        return $this->render('quiz_page/index.html.twig', [
            'allQuiz' => $allQuiz,
        ]);
    }
}
