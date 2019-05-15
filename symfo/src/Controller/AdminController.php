<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Garden;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

    /**
     * @Route("api/garden/{garden}/admin/status/show", name="admin_show_all", methods={"GET"})
     */
    public function showAll(Garden $garden, UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {

            $currentUser = $this->get('security.token_storage')->getToken()->getUser();
            $currentUserRoles = $currentUser->getRoles();

            $role = $roleRepository->findBy(['label' => 'administrateur']);

            // dump($currentUserRoles);

            foreach ($role as $roleName) {
                $userRole = $roleName->getName();
                // dump($user);
                if (array_search($userRole, $currentUserRoles) !== false) {

                    $data = $this->get('serializer')->serialize($gardenUsers, 'json', [
                        'groups' => ['admin']
                    ]);

                    $response = new Response($data);

                    $response->headers->set('Content-Type', 'application/json');

                    return $response;
                } else {
                    return new JsonResponse("Aucun user en attente de validation", 200);
                }
            }
            return new JsonResponse("Vous n'êtes pas autorisé à accéder à ce contenu", 500);
        }
        return new JsonResponse("Vous n'êtes pas membre de ce jardin", 500);
    }


    /**
     * @Route("api/garden/{garden}/admin/status/show/validation", name="admin_status_a_valider", methods={"GET"})
     */
    public function showStatus(Garden $garden, UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {

            $currentUser = $this->get('security.token_storage')->getToken()->getUser();
            $currentUserRoles = $currentUser->getRoles();

            $role = $roleRepository->findBy(['label' => 'administrateur']);

            // dump($currentUserRoles);

            foreach ($role as $roleName) {
                $userRole = $roleName->getName();
                // dump($user);
                if (array_search($userRole, $currentUserRoles) !== false) {


                    $usersToBeAuthorized = $userRepository->findby(['statut' => 'à valider']);

                    // dd($usersToBeAuthorized);

                    $compare = function ($gardenUsers, $usersToBeAuthorized) {
                        return spl_object_hash($gardenUsers) <=> spl_object_hash($usersToBeAuthorized);
                    };

                    $resultat = array_uintersect($gardenUsers, $usersToBeAuthorized, $compare);

                    if (!empty($resultat)) {

                        $data = $this->get('serializer')->serialize($resultat, 'json', [
                            'groups' => ['admin']
                        ]);

                        $response = new Response($data);

                        $response->headers->set('Content-Type', 'application/json');

                        return $response;
                    } else {
                        return new JsonResponse("Aucun user en attente de validation", 200);
                    }
                }
                return new JsonResponse("Vous n'êtes pas autorisé à accéder à ce contenu", 500);
            }
        }
        return new JsonResponse("Vous n'êtes pas membre de ce jardin", 500);
    }


    /**
     * @Route("api/garden/{garden}/admin/status/user/{id}/edit", name="admin_status_validation", methods={"POST"})
     */
    public function validation(Garden $garden, UserRepository $userRepository, RoleRepository $roleRepository, Request $request, ValidatorInterface $validator, ObjectManager $manager, User $user)
    {


        $gardenUsers = $garden->getUsers()->getValues();
        $userToken = [];
        $userToken[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($userToken, $gardenUsers) {
            return spl_object_hash($userToken) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($userToken, $gardenUsers, $compare))) {

            $currentUser = $this->get('security.token_storage')->getToken()->getUser();
            $currentUserRoles = $currentUser->getRoles();

            $role = $roleRepository->findBy(['label' => 'administrateur']);

            // dump($currentUserRoles);

            foreach ($role as $roleName) {
                $userRole = $roleName->getName();
                // dump($user);
                if (array_search($userRole, $currentUserRoles) !== false) {


                    $userToBeAuthorized[] = $user;

                    // dd($userToBeAuthorized);
                    $gardenUsers = $garden->getUsers()->getValues();

                    $compare = function ($userToBeAuthorized, $gardenUsers) {
                        return spl_object_hash($userToBeAuthorized) <=> spl_object_hash($gardenUsers);
                    };


                    if (!empty(array_uintersect($userToBeAuthorized, $gardenUsers, $compare))) {

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

                        if ($statut == 'ok') {

                            $member = $roleRepository->findBy(['label' => 'membre']);

                            foreach ($member as $roleMember) {

                                $user->addRole($roleMember);

                                $manager->persist($user);
                                $manager->flush();

                                return new JsonResponse("le statut et le rôle ont bien été modifiés", 200);
                            }
                        }
                        $garden->removeUser($user);
                        $manager->persist($garden);
                        $manager->persist($user);
                        $manager->flush();

                        return new JsonResponse("le statut a bien été modifié", 200);
                    } else {
                        return new JsonResponse("Votre statut à déjà été confirmé", 500);
                    }
                } else
                    return new JsonResponse("Vous n' êtes pas autorisé à accéder à cette page", 500);
            }
        }
    }

    /**
     * @Route("api/admin/create/role", name="admin_role", methods={"POST"})
     */
    // public function create_role(ObjectManager $manager, Request $request, ValidatorInterface $validator)
    // {

    //     $content = $request->getContent();

    //     $role = $this->get('serializer')->deserialize($content, Role::class, 'json');

    //     $errors = $validator->validate($role);

    //     if (count($errors) > 0) {
    //         dd($errors);
    //     }

    //     $manager->persist($role);
    //     $manager->flush();

    //     return new JsonResponse('Nouveau rôle créé', 200);
    // }

    /**
     * @Route("api/garden/{garden}/admin/charte/add", name="admin_add_charte", methods={"POST"})
     */
    public function addCharte(Garden $garden, UserRepository $userRepository, RoleRepository $roleRepository, Request $request, ValidatorInterface $validator, ObjectManager $manager)
    {


        $gardenUsers = $garden->getUsers()->getValues();
        $userToken = [];
        $userToken[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($userToken, $gardenUsers) {
            return spl_object_hash($userToken) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($userToken, $gardenUsers, $compare))) {

            $currentUser = $this->get('security.token_storage')->getToken()->getUser();
            $currentUserRoles = $currentUser->getRoles();

            $role = $roleRepository->findBy(['label' => 'administrateur']);

            // dump($currentUserRoles);

            foreach ($role as $roleName) {
                $userRole = $roleName->getName();
                // dump($user);
                if (array_search($userRole, $currentUserRoles) !== false) {

                    $file = $request->files->get('file');
                    // dd($file);

                    if (!is_null($file)) {

                        $fileExtension = $file->guessExtension();
                        $gardenId = $garden->getId();

                        $filename = $gardenId . "." . $fileExtension;
                        $path = $this->getParameter('charte_directory');



                        if ($filename == $garden->getCharte()) {
                            $fs = new Filesystem();
                            $fs->remove(__DIR__ . "/../../public/uploads/" . $filename);
                        }

                        $file->move(
                            $path,
                            $filename
                        );

                        $garden->setCharte($filename);
                        $manager->persist($garden);
                        $manager->flush();

                        return new JsonResponse("La charte a bien été ajouté à votre jardin", 200);
                    }


                    return new JsonResponse("Votre charte a bien été éditée", 500);
                }
            }
            return new JsonResponse("Vous n' êtes pas autorisé à accéder à cette page", 500);
        }
    }
    /**
     * @Route("api/garden/{garden}/admin/charte/show", name="admin_show_charte", methods={"GET"})
     */
    public function showCharte(Garden $garden, UserRepository $userRepository, RoleRepository $roleRepository, Request $request, ValidatorInterface $validator, ObjectManager $manager)
    {
        $gardenUsers = $garden->getUsers()->getValues();
        $userToken = [];
        $userToken[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($userToken, $gardenUsers) {
            return spl_object_hash($userToken) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($userToken, $gardenUsers, $compare))) {

            $currentUser = $this->get('security.token_storage')->getToken()->getUser();
            $currentUserRoles = $currentUser->getRoles();

            $role = $roleRepository->findBy(['label' => 'administrateur']);

            // dump($currentUserRoles);

            foreach ($role as $roleName) {
                $userRole = $roleName->getName();
                // dump($user);
                if (array_search($userRole, $currentUserRoles) !== false) {

                    $finder = new Finder();
                    $finder->in(__DIR__ . "/../../public/uploads");

                    $finderFile = $finder->files()->name($garden->getCharte());

                    if ($garden->getCharte() !== null) {
                        // dd($finderFile);
                        foreach ($finderFile as $file) {
                            // dump($finder);
                            // dd($file);
                            $absoluteFilePath = $file->getRealPath();
                            // dd($absoluteFilePath);

                            $response = new BinaryFileResponse($absoluteFilePath);
                            return $response;
                        }
                    }
                    return new JsonResponse("vous n'avez pas enregistrer de charte", 500);
                }
            }
            return new JsonResponse("Vous n' êtes pas autorisé à accéder à cette page", 500);
        }
    }

    /**
     * @Route("api/garden/{garden}/admin/charte/delete", name="admin_delete_charte", methods={"POST"})
     */
    public function deleteCharte(Garden $garden, UserRepository $userRepository, RoleRepository $roleRepository, Request $request, ValidatorInterface $validator, ObjectManager $manager)
    {


        $gardenUsers = $garden->getUsers()->getValues();
        $userToken = [];
        $userToken[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($userToken, $gardenUsers) {
            return spl_object_hash($userToken) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($userToken, $gardenUsers, $compare))) {

            $currentUser = $this->get('security.token_storage')->getToken()->getUser();
            $currentUserRoles = $currentUser->getRoles();

            $role = $roleRepository->findBy(['label' => 'administrateur']);

            // dump($currentUserRoles);

            foreach ($role as $roleName) {
                $userRole = $roleName->getName();
                // dump($user);
                if (array_search($userRole, $currentUserRoles) !== false) {

                    $fs = new Filesystem();
                    $fs->remove(__DIR__ . "/../../public/uploads/" . $garden->getCharte());
                    $garden->setCharte(null);

                    $manager->persist($garden);
                    $manager->flush();




                    return new JsonResponse("La charte a bien été supprimée", 200);
                }

                return new JsonResponse("Vous n' êtes pas autorisé à accéder à cette page", 500);
            }
        }
    }
}
