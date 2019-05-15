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
     * @Route("/api/garden/{garden}/event", name="create_event", methods={"GET","POST"})
     * @ParamConverter("garden", options={"id" = "garden"})
     */
    public function create(Garden $garden, Request $request, ObjectManager $manager, ValidatorInterface $validator)
    {

        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {


            $content = $request->getContent();

            $event = $this->get('serializer')->deserialize($content, Event::class, 'json');
            $errors = $validator->validate($event);
            if (count($errors) > 0) {
                dd($errors);
            }

            $user = $this->get('security.token_storage')->getToken()->getUser();
            $event->setUser($user);
            $event->setGarden($garden);

            $manager->persist($event);

            $manager->flush();
            return new JsonResponse("L'event à bien été créé", 200);
        }
        return new JsonResponse("Vous n'êtes pas autorisé à créer un event", 500);
    }

    /**
     * @Route("api/garden/{garden}/events", name="show_events", methods={"GET"})
     * @ParamConverter("garden", options={"id" = "garden"})
     * @ParamConverter("event", options={"id" = "id"})
     */
    public function showAll(Garden $garden, EventRepository $eventRepository)
    {

        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {

            $events = $garden->getEvents();
            $data = $this->get('serializer')->serialize($events, 'json', ['groups' => ['event']]);
            $response  = new Response($data);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        return new JsonResponse("Vous n'êtes pas autorisé à voir cet event", 500);
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

        if ((!empty(array_uintersect($user, $gardenUsers, $compare))) && $event->getGarden($garden) == $garden) {

            $data = $this->get('serializer')->serialize($event, 'json', ['groups' => ['event']]);
            $response  = new Response($data);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        return new JsonResponse("Vous n'êtes pas autorisé à voir cet event", 500);
    }


    // return new JsonResponse(["Vous n'êtes pas autorisé à voir cette événement"], 500);


    /**
     * @Route("api/garden/{garden}/event/{id}/edit", name="edit_event", methods={"GET"})
     */
    public function edit(Garden $garden, Event $event, Request $request, ObjectManager $manager, ValidatorInterface $validator, RoleRepository $roleRepository)
    {

        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
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
        return new JsonResponse(["error" => "Vous n'êtes pas autorisé à modifier car vous n'être pas membre du jardin"], 500);
    }

    /**
     * @Route("api/garden/{garden}/event/{id}/edit", name="edit_event_post", methods={"POST"})
     */
    public function edit_post(Garden $garden, Event $event, Request $request, ObjectManager $manager, ValidatorInterface $validator, EventRepository $eventRepository, RoleRepository $roleRepository)
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
            $currentUser = $this->get('security.token_storage')->getToken()->getUser();
            $currentUserRoles = $currentUser->getRoles();

            $role = $roleRepository->findBy(['label' => 'administrateur']);

            foreach ($role as $roleName) {
                $user = $roleName->getName();
                dump($user);
                if (array_search($user, $currentUserRoles) !== false || $currentUser == $event->getUser()) {

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

                    if ($description !== null) {

                        $event->setDescription($description);
                    }
                    if ($title !== null) {

                        $event->setTitle($title);
                    }
                    if ($startDate !== null) {

                        $event->setStartDate($startDate);
                    }
                    if ($endDate !== null) {

                        $event->setEndDate($endDate);
                    }


                    $manager->persist($event);

                    $manager->flush();


                    return new JsonResponse("l'event a bien été édité", 200);
                }
                return new JsonResponse(["error" => "Vous n'êtes pas autorisé à éditer"], 500);
            }
        }
        return new JsonResponse("Vous n'êtes pas membre de ce jardin", 500);
    }


    /**
     * @Route("api/garden/{garden}/event/{id}/delete", name="delete_event", methods={"POST"})
     */
    public function delete_post(Garden $garden, Event $event, Request $request, ObjectManager $manager, ValidatorInterface $validator, EventRepository $eventRepository, RoleRepository
    $roleRepository)
    {

        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
            $currentUser = $this->get('security.token_storage')->getToken()->getUser();
            $currentUserRoles = $currentUser->getRoles();

            $role = $roleRepository->findBy(['label' => 'administrateur']);

            foreach ($role as $roleName) {
                $user = $roleName->getName();
                dump($user);
                if (array_search($user, $currentUserRoles) !== false || $currentUser == $event->getUser()) {
                    $manager->remove($event);
                    $manager->flush();
                    return new JsonResponse('supprimé', 200);
                } else {
                    return new JsonResponse(["error" => "Vous n'êtes pas autorisé à supprimer"], 500);
                }
            }
        }
    }
}
