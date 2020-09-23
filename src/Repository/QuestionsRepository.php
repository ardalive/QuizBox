<?php

namespace App\Repository;

use App\Entity\Questions;
use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Questions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questions[]    findAll()
 * @method Questions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questions::class);
    }

    /**
     * @return Questions[] Returns an array of Quiz objects
     */

    public function findQuestionsByQuizID(int $quizID)
    {
        $qb = $this->createQueryBuilder('questions')
            ->join('questions.quizID', 'quiz')
            ->addSelect('quiz')
            ->andWhere('quiz.id like :id')
            ->setParameter('id', '%'.$quizID.'%')
        ;
        $query = $qb->getQuery();
        return $query->execute();
    }

    /**
     * @return Questions[] Returns Questions array
     */

    public function findQuestionByIdAndQuizId(int $quizID, int $questionID)
    {
        $qb = $this->createQueryBuilder('questions')
            ->join('questions.quizID', 'quiz')
            ->join('questions.answers', 'answer')
            ->addSelect('quiz')
            ->addSelect('answer')
            ->andWhere('quiz.id like :quizID')
            ->andWhere('questions.id like :questionID')
            ->setParameter('quizID', '%'.$quizID.'%')
            ->setParameter('questionID', '%'.$questionID.'%')
        ;
        $query = $qb->getQuery();
        return $query->execute();
    }


    /*
    public function findOneBySomeField($value): ?Questions
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
