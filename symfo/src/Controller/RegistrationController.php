<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Garden;
use App\Form\AdminType;
use App\Form\RegistrationUserType;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/register/admin", name="app_register")
     */
    public function registerAdmin(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $userGarden = [
            'user' => new User(),
            'garden' => new Garden()
        ];
        


        $form = $this->createForm(AdminType::class, $userGarden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            
            $hashedPassword = $passwordEncoder->encodePassword($userGarden['user'], $userGarden['user']->getPassword());
            $userGarden['user']->setPassword($hashedPassword);
         //   $userGarden['user']->addRole('ROLE_ADMIN'); set rôle admin à faire 


            $userGarden['user']->setStatut('ok');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userGarden['user']);
            $entityManager->persist($userGarden['garden']);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $userGarden['user'],
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/user", name="registration")
     */
    public function registerUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, User $user = null) : Response
    {
        // if($user == null){  
        //     $user = new User(); 
        // }

        $user = new User();

        $form= $this->createForm(RegistrationUserType::class, $user);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
        

            $hashedPassword = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $user->setStatut('en attente');

            $manager->persist($user);
            $manager->flush();
 
            $this->addFlash(
                'success',
                'Votre compte à bien été créé, vous pouvez vous connecter'
             );
 dump($user);
            exit;  
             return $this->render('base.html.twig');
         }
 
         return $this->render('registration/index.html.twig', [
             'form' => $form->createView(),
             
         ]);
    }

}
