<?php

namespace App\Controller;

use App\Entity\User;
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

class VacancyController extends AbstractController
{
    /**
     * @Route("api/absence/add", name="create_vacancy", methods={"POST"})
     */
    public function vacancy(Request $request, ObjectManager $manager, ValidatorInterface $validator)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $content = $request->getContent();
        $vacancy = $this->get('serializer')->deserialize($content, Vacancy::class, 'json');

        $errors = $validator->validate($vacancy);

        if (count($errors) > 0) {
            dd($errors);
        }

        $vacancy->setUser($user);

        $manager->persist($vacancy);
        $manager->flush();

        return new JsonResponse("L'absence à bien été créée", 200);
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
     * @Route("api/absence/{id}", name="show_vacancy", methods={"GET"})
     */
    public function show(Vacancy $vacancy, Request $request, ObjectManager $manager)
    {
        $data = $this->get('serializer')->serialize($vacancy, 'json', ['groups' => ['vacancy']]);
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("api/absence/{id}/edit", name="edit_vacancy", methods={"GET"})
     */
    public function vacancyEditGet(Vacancy $vacancy, Request $request, ObjectManager $manager)
    {
        $data = $this->get('serializer')->serialize($vacancy, 'json', ['groups' => ['vacancy']]);
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
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
