<?php

namespace App\Controller;

use App\Form\SubstitutionType;
use App\Entity\VacancySubstitute;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Vacancy;

class SubstitutionController extends AbstractController
{
    /**
     * @Route("/absence/{id}/remplacement", name="create_substitution")
     */
    public function substitution(Request $request, ObjectManager $manager, Vacancy $vacancy)
    {
        
        $substitution = new VacancySubstitute();

        $form = $this->createForm(SubstitutionType::class, $substitution);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $substitution->setVacancy($vacancy);
            $substitution->setUser($this->getUser());

            
            $manager->persist($substitution);
            $manager->flush();


            return $this->redirectToRoute('registration');
        }
        
        return $this->render('substitution/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
