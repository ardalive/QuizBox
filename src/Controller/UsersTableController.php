<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Questions;
use App\Entity\User;
use App\Repository\UserRepository;
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
    public function usersTable(UserRepository $userRepository, EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request) :Response
    {
        $error = $request->query->get('error', '');
        if(count($request->request->all())>0){
            if($request->request->get('id')>0){
                $users = $userRepository->findByFilters($request->request->all());
            }
            else{
                $users = $userRepository->findBySoftFilters($request->request->all());
            }
        }
        else $users = $userRepository->findAll();


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


        return $this->render('users_table/users_table.html.twig', [
            'users' => $users,
            'error' => $error,
//        return $this->render('users_table/users_table.html.twig', [
//            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route ("/promote", name="promote")
     */
    public function promoteById(UserRepository $userRepository, Request $request) :Response
    {
        $error = '';
        $user=$userRepository->findOneBy($request->request->all());
        if(isset($user)){
            $user->setRoles(['ROLE_ADMIN']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        else{
            $error = 'No user with id = '.$request->request->get('id').' found';
        }
        return $this->redirectToRoute('users_table',['error'=>$error]);
    }

    /**
     * @Route ("/status", name="status")
     */
    public function switchStatus(UserRepository $userRepository, Request $request) :Response
    {
        $response = [];
        $requestUserId = $request->request->get('user_id');
        $user=$userRepository->findOneBy(['id'=>$requestUserId]);
        if(isset($user)){
            $user->setIsActive(!$user->isActive());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $response['user_id'] = $requestUserId;
            $response['status'] = $user->isActive();
        }
        else{
            $response['error'] = 'No user with id = '.$request->request->get('id').' found';
        }
        return new JsonResponse($response);
    }

}
