<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
     * @Route("api/admin/status/user/{id}/validation", name="admin_status_validation", methods={"POST"})
     */
    public function validation(UserRepository $userRepository, RoleRepository $roleRepository, Request $request, ValidatorInterface $validator, ObjectManager $manager, User $user)
    {


        $currentUser = $this->get('security.token_storage')->getToken()->getUser();


        $role = $roleRepository->findBy(['label' => 'administrateur']);
        $currentUserRoles = $currentUser->getRoles();


        foreach ($role as $roleName) {
            $userRole = $roleName->getName();

            if (array_search($userRole, $currentUserRoles) !== false) {

                $usersToBeAuthorized = $userRepository->findby(['statut' => 'à valider']);

                if (array_search($user, $usersToBeAuthorized) !== false) {

                    $content = $request->getContent();
                    dump($content);
                    $data = $this->get('serializer')->deserialize($content, User::class, 'json');
                    dump($data);

                    $errors = $validator->validate($data);

                    if (count($errors) > 0) {
                        dd($errors);
                    }

                    $statut = $data->getStatut();

                    $user->setStatut($statut);


                    $manager->persist($user);
                    $manager->flush();

                    return new JsonResponse("le statut à bien été modifié", 200);
                } else {
                    return new JsonResponse("Votre statut à déjà été confirmé", 500);
                }
            } else
                return new JsonResponse("Vous n' êtes pas autorisé à accéder à cette page", 500);
        }
    }
}
