<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_show")
     */
    public function profile(SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        //$userGardens = $user->getGardens();

        // dd($user);
        
        $userJson = $serializer->serialize($user, 'json', [
            'skip_null_values' => true,
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return JsonResponse::fromJsonString($userJson);
    }

}