<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Entity\Garden;
use App\Form\RegistrationUserType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function registerAdmin(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
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
     * @Route("/registration/user", name="registration")
     */
    public function registerUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, User $user = null) : Response
    {

            $user = new User(); 
 
         $form= $this->createForm(RegistrationUserType::class, $user);

         $form->handleRequest($request);
        
         if($form->isSubmitted() && $form->isValid()){
            
             $hashedPassword = $encoder->encodePassword($user, $user->getPassword());
             $user->setPassword($hashedPassword);
             $user->setCreatedAt(new \DateTime());
             $user->setUpdatedAt(new \DateTime());
             $user->setStatut('à valider');

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
