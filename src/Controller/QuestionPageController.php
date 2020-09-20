<?php

namespace App\Controller;

use App\Entity\Questions;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuestionPageController extends AbstractController
{
    /**
     * @Route("/admin/questions", name="question_page")
     */
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request)
    {
//        $allQuestion = $entityManager->getRepository(Questions::class)->findAll();
//
//        if (!$allQuestion) {
//            throw $this->createNotFoundException(
//                'Not found :('
//            );
//        }

        $dql   = "SELECT a FROM App:Questions a";
        $query = $entityManager->createQuery($dql);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('question_page/index.html.twig', [
           // 'allQuestion' => $allQuestion,
            'pagination' => $pagination,
        ]);
    }
}
