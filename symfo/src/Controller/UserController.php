<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Garden;
use App\Repository\GardenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

        if ($user == $this->get('security.token_storage')->getToken()->getUser()) {
            $user = $serializer->serialize($user, 'json', [
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
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer, UserPasswordEncoderInterface $encoder)
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

            $name = $editedUser->getName();
            if ($name != null)
            {
                $user->setName($name);
            }

            $password = $editedUser->getPassword();
            $encodedPassword = $encoder->encodePassword($user, $password);
            if ($password =! null)
            {
                $user->setPassword($encodedPassword);
            }

            $phone = $editedUser->getPhone();
            if ($phone != null)
            {
                $user->setPhone($phone);
            }

            $address = $editedUser->getAddress();
            if ($address != null)
            {
                $user->setAddress($address);
            }

            $user->setUpdatedAt(new \Datetime());
            
            $entityManager->merge($user);
            $entityManager->persist($user);
            $entityManager->flush();
            
            return JsonResponse::fromJsonString('message: Votre profil a été modifiée', 200);
        }
        
        return JsonResponse::fromJsonString('message: Vous n\'êtes pas autorisé à accéder à cette page', 403);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(ObjectManager $objectManager, User $user)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($user == $currentUser) {
            $objectManager->remove($user);
            $objectManager->flush();
            
            return JsonResponse::fromJsonString('message: Votre profil a été supprimé', 200);
        }

        //$userRole = $currentUser->getRoles();
        //dd($userRole);
        foreach ($currentUser->getRoles() as $key => $value) {
            if ($value === "ROLE_ADMIN") {
                $objectManager->remove($user);
                $objectManager->flush();
                
                return JsonResponse::fromJsonString('message: Ce profil a été supprimé', 200);
            }
        }       

        return JsonResponse::fromJsonString('message: Vous n\'êtes pas autorisé à supprimer ce membre', 406);
    }
}   