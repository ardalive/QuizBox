<?php

namespace App\Repository;

use App\Entity\Questions;
use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */

//    /**
//     * @return Quiz[] Returns Quiz
//     */
//
//    public function findCustom()
//    {
//        $qb = $this->createQueryBuilder('quiz')
//            ->join('quiz.questionID', 'question')
//            ->join('questions.answers', 'answer')
//            ->addSelect('question')
//            ->addSelect('answer')
//            ->andWhere('quiz.id like :quizID')
//            ->andWhere('questions.id like :questionID')
//            ->setParameter('quizID', '%'.$quizID.'%')
//            ->setParameter('questionID', '%'.$questionID.'%')
//        ;
//        $query = $qb->getQuery();
//        return $query->execute();
//    }

    /*
    public function findOneBySomeField($value): ?Quiz
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
