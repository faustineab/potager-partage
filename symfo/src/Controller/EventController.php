<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="create_event", methods={"GET","POST"})
     */


    public function create(Request $request, ObjectManager $manager, ValidatorInterface $validator)
    {
        dump($request);
        $content = $request->getContent();

        $event = $this->get('serializer')->deserialize($content, Event::class, 'json');

        $errors = $validator->validate($event);

        if (count($errors) > 0) {
            dd($errors);
        }

        $manager->persist($event);
        $manager->flush();

        return $this->redirectToRoute('show_event', [
            'id' => $event->getId(),
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/event/{id}", name="show_event", methods={"GET"})
     */
    public function show(Event $event, Request $request, ObjectManager $manager)
    {
        $data = $this->get('serializer')->serialize($event, 'json', ['groups' => ['event']]);
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
