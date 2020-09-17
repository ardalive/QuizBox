<?php

namespace App\Controller;

use App\Entity\Questions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuestionPageController extends AbstractController
{
    /**
     * @Route("/admin/questions", name="question_page")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $allQuestion = $entityManager->getRepository(Questions::class)->findAll();

        if (!$allQuestion) {
            throw $this->createNotFoundException(
                'Not found :('
            );
        }

        return $this->render('question_page/index.html.twig', [
            'allQuestion' => $allQuestion,

        ]);
    }
}
