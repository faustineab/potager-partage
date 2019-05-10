<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminController extends AbstractController
{
    /**
     * @Route("api/admin/status/show/validation", name="admin_status_validation", methods={"GET"})
     */
    public function showStatus(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        $role = $roleRepository->findBy(['label' => 'administrateur']);
        $currentUserRoles = $currentUser->getRoles();
        dump($currentUserRoles);

        foreach ($role as $roleName) {
            $user = $roleName->getName();
            dump($user);
            if (array_search($user, $currentUserRoles) !== false) {

                $usersToBeAuthorized = $userRepository->findby(['statut' => 'à valider']);

                $data = $this->get('serializer')->serialize($usersToBeAuthorized, 'json', [
                    'groups' => ['admin']
                ]);
                $response = new Response($data);

                $response->headers->set('Content-Type', 'application/json');

                return $response;
            } else {
                return new JsonResponse("Vous n' êtes pas autorisé à accéder à cette page", 500);
            }
        }
    }

    /**
     * @Route("api/admin/status/validation", name="admin_status_validation", methods={"POST"})
     */
    public function validation(UserRepository $userRepository, RoleRepository $roleRepository, Request $request)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        $role = $roleRepository->findBy(['label' => 'administrateur']);
        $currentUserRoles = $currentUser->getRoles();


        foreach ($role as $roleName) {
            $user = $roleName->getName();

            if (array_search($user, $currentUserRoles) !== false) {

                $statut = $request->getContent();
                dump($statut);
                $data = $this->get('serializer')->deserialize($statut, User::class, 'json');
                dd($data);


                return $response;
            } else {
                return new JsonResponse("Vous n' êtes pas autorisé à accéder à cette page", 500);
            }
        }
    }
}
