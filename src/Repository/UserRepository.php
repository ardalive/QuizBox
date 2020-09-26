<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Query\Expr;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return User[] Returns an array of User objects
     */

    public function findByFilters(array $filters)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.email like :email')
            ->andWhere('u.name like :name')
            ->andWhere('u.id = :id')
//            ->andWhere('u.roles like :roles')
            ->setParameter('email', '%'.$filters['email'].'%')
            ->setParameter('name', '%'.$filters['name'].'%')
            ->setParameter('id', $filters['id'])
//            ->setParameter('roles', '%'.(isset($filters['admin'])?'ROLE_ADMIN':'').'%')
            ->orderBy('u.id', 'ASC');
        $query = $qb->getQuery();
        return $query->execute();
    }
    /**
     * @return User[] Returns an array of User objects
     */

    public function findBySoftFilters(array $filters)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.email like :email')
            ->andWhere('u.name like :name')
            ->andWhere('u.id like :id')
            ->setParameter('email', '%'.$filters['email'].'%')
            ->setParameter('name', '%'.$filters['name'].'%')
            ->setParameter('id', '%'.$filters['id'].'%')
            ->orderBy('u.id', 'ASC');
        $query = $qb->getQuery();
        return $query->execute();
    }

}
