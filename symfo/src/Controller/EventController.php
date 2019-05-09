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
use App\Repository\EventRepository;


class EventController extends AbstractController
{
    /**
     * @Route("/api/event", name="create_event", methods={"GET","POST"})
     */
    public function create(Request $request, ObjectManager $manager, ValidatorInterface $validator)
    {
        $content = $request->getContent();
        $event = $this->get('serializer')->deserialize($content, Event::class, 'json');
        $errors = $validator->validate($event);
        if (count($errors) > 0) {
            dd($errors);
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $event->setUser($user);
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
    /**
     * @Route("/event/{id}/edit", name="edit_event", methods={"GET"})
     */
    public function edit(Event $event, Request $request, ObjectManager $manager, ValidatorInterface $validator)
    {
        $data = $this->get('serializer')->serialize($event, 'json', ['groups' => ['event']]);
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("api/event/{id}/edit", name="edit_event_post", methods={"POST"})
     */
    public function edit_post(Event $event, Request $request, ObjectManager $manager, ValidatorInterface $validator, EventRepository $eventRepository)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user == $event->getUser()) {
            $content = $request->getContent();
            $currentEvent = $this->get('serializer')->deserialize($content, Event::class, 'json');
            dump($currentEvent);
            $errors = $validator->validate($event);
            if (count($errors) > 0) {
                dd($errors);
            }
            $description = $currentEvent->getDescription();
            $title = $currentEvent->getTitle();
            $startDate = $currentEvent->getStartDate();
            $endDate = $currentEvent->getEndDate();
            $event->setDescription($description)
                ->setTitle($title)
                ->setStartDate($startDate)
                ->setEndDate($endDate);
            // $event->setUser($user);
            $manager->persist($event);
            $manager->flush();
            return $this->redirectToRoute('show_event', [
                'id' => $event->getId(),
            ], Response::HTTP_CREATED);
        } else {
            echo 'non autorisé';
        }
    }
}
