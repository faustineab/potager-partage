<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;

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
        
  
            if($user->getStatut() == 'à valider'){
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
        if($user->getStatut() == 'à valider'){
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
        
  
            if($user->getStatut() == 'validé'){
                $user->setStatut('refusé');
            
                $manager->persist($user);
                $manager->flush();
                
                return $this->render('user/index.html.twig', [
                    'controller_name' => 'UserController',
                ]);
            
            }
            
            if($user->getStatut() == 'refusé'){
                    $user->setStatut('validé');}

                    $manager->persist($user);
                    $manager->flush();
                
                return $this->render('user/index.html.twig', [
                'controller_name' => 'UserController',
            ]);
            }  
        
    
}