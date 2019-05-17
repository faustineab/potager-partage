<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login", methods={"GET","POST"})
     */
    public function login(AuthenticationUtils $utils)
    { }

    /**
     * @Route("api/login", name="app_login_GET", methods={"GET"})
     */
    public function api_login(UserRepository $userRepository)
    {

        $currentUser =  $this->get('security.token_storage')->getToken()->getUser();

        $user = $userRepository->find($currentUser->getId());
        // dump($user);
        $data = $this->get('serializer')->serialize($user, 'json', [
            'groups' => ['login'],
            'circular_reference_handler' => function ($user) {
                return $user->getId();
            },
        ]);

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');
        // dd($response);
        return $response;
    }

    /**
     * @Route("api/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        return JsonResponse::fromJsonString('deconnecté', 200);
    }
}
