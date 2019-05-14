<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Garden;
use App\Entity\Vacancy;
use App\Form\VacancyType;
use App\Repository\VacancyRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class VacancyController extends AbstractController
{
    /**
     * @Route("api/garden/{garden}/absence/add", name="create_vacancy", methods={"POST"})
     */
    public function vacancy(Garden $garden, Request $request, ObjectManager $manager, ValidatorInterface $validator)
    {

        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {

            $user = $this->get('security.token_storage')->getToken()->getUser();

            $content = $request->getContent();
            $vacancy = $this->get('serializer')->deserialize($content, Vacancy::class, 'json');

            $errors = $validator->validate($vacancy);

            if (count($errors) > 0) {
                dd($errors);
            }

            $vacancy->setUser($user);
            $vacancy->setGarden($garden);

            $manager->persist($vacancy);
            $manager->flush();

            return new JsonResponse("L'absence à bien été créée", 200);
        }
        return new JsonResponse("Vous n'êtes pas membre de ce jardin", 200);
    }


    /**
     * @Route("api/user/{id}/absences", name="show_all_vacancies", methods={"GET"})
     */
    public function showAll(User $user, VacancyRepository $vacancyRepository, Request $request, ObjectManager $manager)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if ($user == $currentUser) {

            $vacancies = $user->getVacancies();

            $data = $this->get('serializer')->serialize($vacancies, 'json', ['groups' => ['vacancy']]);
            $response = new Response($data);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à voir cette page"], 500);
        }
    }


    /**
     * @Route("api/garden/{garden}/absences", name="show_all_gardenVacancies", methods={"GET"})
     */
    public function showAllGardenVacancies(Garden $garden, VacancyRepository $vacancyRepository, Request $request, ObjectManager $manager)
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {

            $vacancies = $garden->getVacancies();

            $data = $this->get('serializer')->serialize($vacancies, 'json', ['groups' => ['vacancy']]);
            $response = new Response($data);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à voir cette page"], 500);
        }
    }


    /**
     * @Route("api/garden/{garden}/absence/{id}", name="show_vacancy", methods={"GET"})
     * @ParamConverter("garden", options={"id" = "garden"})
     */
    public function show(Garden $garden, Vacancy $vacancy, Request $request, ObjectManager $manager)
    {
        $gardenUsers = $garden->getUsers()->getValues();
        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        $resultat = array_uintersect(
            $user,
            $gardenUsers,
            $compare
        );

        if ((!empty(array_uintersect($user, $gardenUsers, $compare))) && $vacancy->getGarden($garden) == $garden) {


            $data = $this->get('serializer')->serialize($vacancy, 'json', ['groups' => ['vacancy']]);
            $response = new Response($data);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            return new JsonResponse("Vous n'étes pas autorisé à voir ce contenu", 500);
        }
    }

    /**
     * @Route("api/absence/{id}/edit", name="edit_vacancy", methods={"GET"})
     */
    public function vacancyEditGet(Vacancy $vacancy, Request $request, ObjectManager $manager)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user == $vacancy->getUser()) {

            $data = $this->get('serializer')->serialize($vacancy, 'json', ['groups' => ['vacancy']]);
            $response = new Response($data);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        return new JsonResponse("Vous n'étes pas autorisé à modifier ce contenu", 500);
    }

    /**
     * @Route("api/absence/{id}/edit", name="edit_vacancy_post", methods={"POST"})
     */
    public function vacancyEdit(Vacancy $vacancy, Request $request, ObjectManager $manager, ValidatorInterface $validator)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user == $vacancy->getUser()) {
            $content = $request->getContent();
            $currentVacancy = $this->get('serializer')->deserialize($content, Vacancy::class, 'json');

            $errors = $validator->validate($vacancy);

            if (count($errors) > 0) {
                dd($errors);
            }

            $startDate = $currentVacancy->getStartDate();
            $endDate = $currentVacancy->getEndDate();

            $vacancy->setStartDate($startDate)
                ->setEndDate($endDate);

            $manager->persist($vacancy);
            $manager->flush();

            return new JsonResponse("L'absence à bien été édité", 200);
        } else {
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à éditer"], 500);
        }
    }
    /**
     * @Route("api/absence/{id}/delete", name="delete_vacancy", methods={"POST"})
     */
    public function delete(Vacancy $vacancy, ObjectManager $manager)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user == $vacancy->getUser()) {
            $manager->remove($vacancy);
            $manager->flush();

            return new JsonResponse('supprimé', 200);
        } else {
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à supprimer"], 500);
        }
    }
}
