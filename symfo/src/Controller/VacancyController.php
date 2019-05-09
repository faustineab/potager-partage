<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vacancy;
use App\Form\VacancyType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VacancyController extends AbstractController
{
    /**
     * @Route("/user/{id}/absence/add", name="create_vacancy")
     */
    public function vacancy(User $user, Request $request, ObjectManager $manager)
    {
        $vacancy = new Vacancy();
        
        $form = $this->createForm(VacancyType::class, $vacancy);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $vacancy->setUser($user);
            
            $manager->persist($vacancy);
            $manager->flush();


        }

        return $this->render('vacancy/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
