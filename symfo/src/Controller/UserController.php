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
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



/**
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function profile(User $user, SerializerInterface $serializer)
    {

        if ($user == $this->get('security.token_storage')->getToken()->getUser()) 
        {
            $user = $serializer->serialize($user, 'json',[
                'groups' => 'user',
                'circular_reference_handler' => function ($user) {
                   return $user->getId();
                }
            ]);
         
            return JsonResponse::fromJsonString($user);
        }        

        return JsonResponse::fromJsonString('Vous n\'êtes pas autorisé à visualiser cette page', 403);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"PUT"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
     if ($user == $this->get('security.token_storage')->getToken()->getUser()) 
        {
            $content = $request->getContent();

            $editedUser = $serializer->deserialize($content, User::class, 'json');
            
            $errors = $validator->validate($editedUser);
            if (count($errors) > 0)
            {
                foreach ($errors as $error) 
                {
                    return new JsonResponse($error, 304);
                }
            }
            
            $email = $editedUser->getEmail();
            if ($email != null)
            {
                $user->setEmail($email);
            }

            $password = 

            $user->setUpdatedAt(new \Datetime());
            
            $entityManager->merge($user);
            $entityManager->persist($user);
            $entityManager->flush();
        }      

            
            // foreach ($editedQuestion->getTags() as $editedTag) 
            // {
            //     if ($editedTag = $user->getTags()) {
            //         $editedQuestion->addTag($editedTag);
            //     }
            //     if ($editedTag != $user->getTags()) {
            //         $editedQuestion->removeTag($editedTag);
            //     }
            // }
            
            
            return JsonResponse::fromJsonString('message: Votre question a été modifiée', 200);
    }

        return JsonResponse::fromJsonString('message: Vous n\'êtes pas autorisé à modifier cette question', 403);
    }
    

    
}


