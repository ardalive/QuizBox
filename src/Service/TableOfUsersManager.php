<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TableOfUsersManager extends AbstractController
{
    private $userID;
    private $email;
    private $userName;

    public function initializeParams(array $paramsArray) :void
    {
        $this->userName = array_key_exists('name', $paramsArray) ? $paramsArray['name'] : "";
        $this->email = array_key_exists('email', $paramsArray) ? $paramsArray['email'] : "";
        $this->userID = array_key_exists('id', $paramsArray) ? $paramsArray['id'] : "";
    }

    public function queryEmail(QueryBuilder $queryBuilder):QueryBuilder
    {
        if(strlen($this->email) > 0){
            $queryBuilder
                ->andWhere('user.email LIKE :email')
                ->setParameter('email', '%'.$this->email.'%')
            ;
        }
        return $queryBuilder;
    }
    public function queryName(QueryBuilder $queryBuilder):QueryBuilder
    {
        if(strlen($this->userName) > 0){
            $queryBuilder
                ->andWhere('user.name LIKE :name')
                ->setParameter('name', '%'.$this->userName.'%')
            ;
        }
        return $queryBuilder;
    }
    public function queryID(QueryBuilder $queryBuilder):QueryBuilder
    {
        if(strlen($this->userID) > 0){
            $queryBuilder
                ->andWhere('user.id = :id')
                ->setParameter('id', $this->userID)
            ;
        }
        return $queryBuilder;
    }

    public function promoteUser(User $user)
    {
        $user->setRoles(['ROLE_ADMIN']);
        $this->updateUser($user);
    }

    public function switchStatus(User $user)
    {
        $user->setIsActive(!$user->isActive());
        $this->updateUser($user);
    }

    public function updateUser(User $user) :void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

}