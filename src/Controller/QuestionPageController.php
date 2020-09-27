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
     * @Route("/admin/questions")
     */
    public function indexNoLocale()
    {
       return $this->redirectToRoute('question_page', ['_locale' => 'en']);
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/admin/questions", name="question_page")
     */
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request)
    {

        $queryBuilder = $entityManager->getRepository(Questions::class)
            ->createQueryBuilder('question')
            ->join('question.answers', 'answer')
           // ->join('question.quizID', 'quiz')
            ->addSelect('answer');
           // ->addSelect('quiz');
        if($request->query->getAlnum('filter')){
            $queryBuilder->where('question.QuestionBody LIKE :body')->setParameter('body', '%'. $request->query->getAlnum('filter') .'%');
        }
        $query = $queryBuilder->getQuery()->getResult();



        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('question_page/index.html.twig', [
           // 'allQuestion' => $allQuestion,
            'pagination' => $pagination,

        ]);
    }
}
