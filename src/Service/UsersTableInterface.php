<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersTableInterface extends AbstractController
{
    public function switchTableFilters(array $filters, UserRepository $userRepository) :array
    {
        if(count($filters)>0){
            return $this->switchForIdFilter($filters, $userRepository);
        }
        else return $userRepository->findAll();
    }
    public function switchForIdFilter(array $filters, UserRepository $userRepository) :array
    {
        if($filters['id']>0){
            return $userRepository->findByFilters($filters);
        }
        else return $userRepository->findBySoftFilters($filters);
    }
    public function updateUser(User $user) :void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }
}