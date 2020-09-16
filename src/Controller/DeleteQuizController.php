<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeleteQuizController extends AbstractController
{
    /**
     * @Route("admin/quiz/delete{id}", name="delete_quiz")
     */
    public function index(int $id)
    {
        return $this->render('delete_quiz/index.html.twig', [
            'controller_name' => 'DeleteQuizController',
        ]);
    }
}
