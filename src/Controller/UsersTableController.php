<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\TableOfUsersManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersTableController extends AbstractController
{
    /**
     * @Route("/{_locale<%app.supported_locales%>}/admin/users", name="users_table")
     */
    public function usersTable(TableOfUsersManager $usersTable, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator) :Response
    {
        $queryBuilder = $entityManager->getRepository(User::class)
            ->createQueryBuilder('user')
            ;
        $usersTable->initializeParams($request->query->all());
        $queryBuilder = $usersTable->queryEmail($queryBuilder);
        $queryBuilder = $usersTable->queryName($queryBuilder);
        $queryBuilder = $usersTable->queryID($queryBuilder);
        $query = $queryBuilder->getQuery()->getResult();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('users_table/users_table.html.twig', [
            'pagination' => $pagination,
            'test'=> $request->query->all()
        ]);
    }

    /**
     * @Route ("/{_locale<%app.supported_locales%>}/admin/users/promote", name="promote")
     */
    public function promoteById(UserRepository $userRepository, Request $request, TableOfUsersManager $usersTable) :Response
    {
        $error = '';
        $user = $userRepository->findOneBy($request->request->all());
        if(isset($user)){
            $usersTable->promoteUser($user);
        }
        else{
            $error = 'No user with id = '.$request->request->get('id').' found';
        }
        return $this->redirectToRoute('users_table',['error'=>$error]);
    }

    /**
     * @Route ("/{_locale<%app.supported_locales%>}/admin/users/status", name="status", methods={"POST"})
     */
    public function switchStatus(UserRepository $userRepository, Request $request, TableOfUsersManager $usersTable) :Response
    {
        $response = [];
        $requestUserId = $request->request->get('user_id');
        $user = $userRepository->findOneBy(['id'=>$requestUserId]);
        if(isset($user)){
            $usersTable->switchStatus($user);
            $response['user_id'] = $requestUserId;
            $response['status'] = $user->isActive();
        }
        else{
            $response['error'] = 'No user with id = '.$request->request->get('id').' found';
        }
        return new JsonResponse($response);
    }

}
