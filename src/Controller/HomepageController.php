<?php

namespace App\Controller;

use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(EntityManagerInterface $entityManager,PaginatorInterface $paginator, Request $request)
    {
//        $allQuiz = $entityManager->getRepository(Quiz::class)->findAll();
//        if (!$allQuiz) {
//            throw $this->createNotFoundException(
//                'Not found :('
//            );
//        }
        $queryBuilder = $entityManager->getRepository(Quiz::class)->createQueryBuilder('quiz');
        if($request->query->getAlnum('filter')){
            $queryBuilder->where('quiz.name LIKE :name')->setParameter('name', '%'. $request->query->getAlnum('filter') .'%');
        }
        $query = $queryBuilder->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('homepage/homepage.html.twig', [
            //'allQuiz' => $allQuiz,
            'pagination' => $pagination,
        ]);
    }
}
