<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Garden;
use App\Repository\GardenRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/user/{id}/validation", name="validation")
     */
    public function validation(User $user, ObjectManager $manager)
    {


        if ($user->getStatut() == 'à valider') {
            $user->setStatut('validé');

            $manager->persist($user);
            $manager->flush();

            return $this->render('user/index.html.twig', [
                'controller_name' => 'UserController',
            ]);
        }
    }

    /**
     * @Route("/user/{id}/refus", name="refus")
     */
    public function refus(User $user, ObjectManager $manager)
    {
        if ($user->getStatut() == 'à valider') {
            $user->setStatut('refusé');

            $manager->persist($user);
            $manager->flush();

            return $this->render('user/index.html.twig', [
                'controller_name' => 'UserController',
            ]);
        }
    }

    /**
     * @Route("/user/{id}/edit", name="validation")
     */
    public function edit(User $user, ObjectManager $manager)
    {


        if ($user->getStatut() == 'validé') {
            $user->setStatut('refusé');

            $manager->persist($user);
            $manager->flush();

            return $this->render('user/index.html.twig', [
                'controller_name' => 'UserController',
            ]);
        }

        if ($user->getStatut() == 'refusé') {
            $user->setStatut('validé');
        }

        $manager->persist($user);
        $manager->flush();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("api/user/{id}/add/garden", name="add_garden", methods={"POST"})
     */
    public function addGarden(User $user, Request $request,  ValidatorInterface $validator, GardenRepository $gardenRepository, ObjectManager $manager)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if ($user == $currentUser) {

            $garden = json_decode($request->getContent(), true);

            dump($garden);


            $errors = $validator->validate($garden);

            if (count($errors) > 0) {
                dd($errors);
            }

            $id = $garden['id'];

            $garden = $gardenRepository->find($id);

            $garden->addUser($currentUser);

            $manager->persist($garden);
            $manager->flush();

            return new JsonResponse('le jardin a été ajouté', 200);
        }
    }
}
