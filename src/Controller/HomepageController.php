<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Repository\PlayerAnswersRepository;
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
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request, PlayerAnswersRepository $playerAnswersRepository)
    {
        $queryBuilder = $entityManager->getRepository(Quiz::class)->createQueryBuilder('quiz');
        $query = $queryBuilder->getQuery()->getResult();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );

        $quizLeaders = [];
        foreach ($pagination->getItems() as $item){
            $quizLeaders[$item->getId()] = $playerAnswersRepository->findLeadersInQuiz($item->getId());
        }

        return $this->render('homepage/homepage.html.twig', [
            'pagination' => $pagination,
            'quizLeaders'=>$quizLeaders
        ]);
    }
}
