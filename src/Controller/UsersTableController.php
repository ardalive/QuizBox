<?php
declare(strict_types=1);
namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UsersTableController extends AbstractController
{
    /**
     * @Route("/users/table", name="users_table")
     */
    public function index(UserRepository $userRepository, Request $request)
    {
        if(count($request->request->all())>0){
            $users = $userRepository->findByFilters($request->request->all());
        }
        else $users = $userRepository->findAll();
        return $this->render('users_table/users_table.html.twig', [
            'users' => $users,
        ]);
    }
}
