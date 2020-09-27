<?php

namespace App\Controller;

use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuizPageController extends AbstractController
{
    /**
     * @Route("/{_locale<%app.supported_locales%>}/admin/quiz", name="quiz_page")
     */
    public function index(EntityManagerInterface $entityManager,PaginatorInterface $paginator, Request $request)
    {
        $queryBuilder = $entityManager->getRepository(Quiz::class)->createQueryBuilder('quiz');
        if($request->query->getAlnum('filter')){
            $queryBuilder->where('quiz.name LIKE :name')->setParameter('name', '%'. $request->query->getAlnum('filter') .'%');
        }
        $query = $queryBuilder->getQuery();


        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('quiz_page/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
