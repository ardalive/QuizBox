<?php

namespace App\Repository;

use App\Entity\PlayerAnswers;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlayerAnswers|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerAnswers|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerAnswers[]    findAll()
 * @method PlayerAnswers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerAnswersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerAnswers::class);
    }

    /**
     * @return PlayerAnswers
     */

    public function findByUserQuizId(array $filters)
    {
        $qb = $this->createQueryBuilder('answers')
            ->join('answers.userRelation', 'user')
            ->join('answers.quizRelation', 'quiz')
            ->addSelect('user')
            ->addSelect('quiz')
            ->andwhere('user.id like :user_id')
            ->andWhere('quiz.id like :quiz_id')
            ->setParameter('user_id', '%'.'11'.'%')
            ->setParameter('quiz_id', '%'.'29'.'%')
//            ->setParameter('user_id', '%'.$filters['user_id'].'%')
//            ->setParameter('quiz_id', '%'.$filters['quiz_id'].'%')
//            ->setMaxResults(1)
        ;
        $query = $qb->getQuery();
        return $query->execute();
    }




    // /**
    //  * @return PlayerAnswers[] Returns an array of PlayerAnswers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlayerAnswers
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
