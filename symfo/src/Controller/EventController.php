<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function create(Request $request, ObjectManager $manager)
    {
        
        $user = $this->getUser();
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event->setUser($user);
            
            $manager->persist($event);
            $manager->flush();

            return $this->redirectToRoute('app_login');

        }
        
        return $this->render('event/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
