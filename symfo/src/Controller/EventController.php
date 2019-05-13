<?php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\Garden;
use App\Form\EventType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Repository\GardenRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


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
     * @Route("api/garden/{garden}/event/{id}", name="show_event", methods={"GET"})
     * @ParamConverter("garden", options={"id" = "garden"})
     * @ParamConverter("event", options={"id" = "id"})
     */
    public function show(Garden $garden, Event $event)
    {

        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {

            $data = $this->get('serializer')->serialize($event, 'json', ['groups' => ['event']]);
            $response  = new Response($data);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }


    // return new JsonResponse(["Vous n'êtes pas autorisé à voir cette événement"], 500);


    /**
     * @Route("api/event/{id}/edit", name="edit_event", methods={"GET"})
     */
    public function edit(Event $event, Request $request, ObjectManager $manager, ValidatorInterface $validator, RoleRepository $roleRepository)
    {

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $currentUserRoles = $currentUser->getRoles();

        $role = $roleRepository->findBy(['label' => 'administrateur']);

        dump($currentUserRoles);

        foreach ($role as $roleName) {
            $user = $roleName->getName();
            dump($user);
            if (array_search($user, $currentUserRoles) !== false || $currentUser == $event->getUser()) {


                $data = $this->get('serializer')->serialize($event, 'json', ['groups' => ['event']]);

                $response = new Response($data);

                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à modifier"], 500);
        }
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
            // dump($currentEvent);

            $errors = $validator->validate($currentEvent);

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
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à éditer"], 500);
        }
    }
    /**
     * @Route("api/event/{id}/delete", name="delete_event", methods={"POST"})
     */
    public function delete_post(Event $event, Request $request, ObjectManager $manager, ValidatorInterface $validator, EventRepository $eventRepository)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user == $event->getUser()) {
            $manager->remove($event);
            $manager->flush();
            return new JsonResponse('supprimé', 200);
        } else {
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à supprimer"], 500);
        }
    }
}
