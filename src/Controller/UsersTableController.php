<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Questions;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\TableOfUsers;
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
     * @Route("/admin/users", name="users_table")
     */
    public function usersTable(TableOfUsers $usersTable, UserRepository $userRepository, Request $request) :Response
    {
        $error = $request->query->get('error', '');
        return $this->render('users_table/users_table.html.twig', [
            'users' => $usersTable->switchTableFilters($request->request->all(), $userRepository),
            'error' => $error,
        ]);


        //args
        //EntityManagerInterface $entityManager, PaginatorInterface $paginator,
//        $queryBuilder = $entityManager->getRepository(User::class)
//            ->createQueryBuilder('user')
//            ;
//        if($request->query->getAlnum('filter')){
//            $queryBuilder
//                ->where('question.QuestionBody LIKE :body')
//                ->setParameter('body', '%'. $request->query->getAlnum('filter') .'%');
//        }
//        $query = $queryBuilder->getQuery()->getResult();
//
//
//
//        $pagination = $paginator->paginate(
//            $query,
//            $request->query->getInt('page', 1),
//            10
//        );
//        return $this->render('users_table/users_table.html.twig', [
//            'pagination' => $pagination,

    }

    /**
     * @Route ("/promote", name="promote")
     */
    public function promoteById(UserRepository $userRepository, Request $request, TableOfUsers $usersTable) :Response
    {
        $error = '';
        $user=$userRepository->findOneBy($request->request->all());
        if(isset($user)){
            $user->setRoles(['ROLE_ADMIN']);
            $usersTable->updateUser($user);
        }
        else{
            $error = 'No user with id = '.$request->request->get('id').' found';
        }
        return $this->redirectToRoute('users_table',['error'=>$error]);
    }

    /**
     * @Route ("/status", name="status")
     */
    public function switchStatus(UserRepository $userRepository, Request $request, TableOfUsers $usersTable) :Response
    {
        $response = [];
        $requestUserId = $request->request->get('user_id');
        $user=$userRepository->findOneBy(['id'=>$requestUserId]);
        if(isset($user)){
            $user->setIsActive(!$user->isActive());
            $usersTable->updateUser($user);

            $response['user_id'] = $requestUserId;
            $response['status'] = $user->isActive();
        }
        else{
            $response['error'] = 'No user with id = '.$request->request->get('id').' found';
        }
        return new JsonResponse($response);
    }

}
