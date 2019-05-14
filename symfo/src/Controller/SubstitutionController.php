<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Garden;
use App\Entity\Vacancy;
use App\Form\SubstitutionType;
use App\Entity\VacancySubstitute;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SubstitutionController extends AbstractController
{
    /**
     * @Route("api/garden/{garden}/absence/{id}/remplacement", name="create_substitution", methods={"POST"})
     *  @ParamConverter("garden", options={"id" = "garden"})
     */
    public function substitution(Garden $garden, Request $request, ObjectManager $manager, Vacancy $vacancy, ValidatorInterface $validator)
    {

        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if ((!empty(array_uintersect($user, $gardenUsers, $compare))) && $vacancy->getGarden($garden) == $garden) {

            $currentUser = $this->get('security.token_storage')->getToken()->getUser();

            $content = $request->getContent();
            $substitution = $this->get('serializer')->deserialize($content, VacancySubstitute::class, 'json');

            $errors = $validator->validate($substitution);

            if (count($errors) > 0) {
                dd($errors);
            }

            $substitution->setVacancy($vacancy);
            $manager->persist($substitution);

            $availableDates = $vacancy->getAvailableDays();

            $substitutionDates = $substitution->getDays();


            if (!empty(array_diff($substitutionDates, $availableDates))) {
                return new JsonResponse("Les dates choisies ne correspondent pas aux dates d' absences", 500);
            }
            if ($substitution->isBookableDate()) {

                $substitution->setUser($currentUser);

                $manager->persist($substitution);
                $manager->flush();
                return new JsonResponse("Le remplacement à bien été créée", 200);
            }
            return new JsonResponse('les dates choisies ont déja été réservées', 500);
        }
        return new JsonResponse("vous n'êtes pas membre de ce jardin ou aucun remplacement ne correspond à votre demande", 500);
    }
    /**
     * @Route("api/garden/{garden}/absence/{id}/remplaçants", name="show_substitutions", methods={"GET"})
     *  @ParamConverter("garden", options={"id" = "garden"})
     */

    //montre tous les remplaçants pour une absence
    public function showSubstitutions(Garden $garden, Vacancy $vacancy)
    {


        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if ((!empty(array_uintersect($user, $gardenUsers, $compare))) && $vacancy->getGarden($garden) == $garden) {


            $substitutions = $this->get('serializer')->serialize($vacancy, 'json', ['groups' => ['vacancy']]);


            $response = new Response($substitutions);

            return $response;
        }
        return new JsonResponse("vous n'êtes pas membre de ce jardin ", 500);
    }

    /**
     * @Route("api/user/{id}/remplacements", name="show_user_substitutions", methods={"GET"})
     */
    //montre tous les remplacement d'un user
    public function showUserSubstitutions(User $user)
    {

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($user == $currentUser) {

            $vacancySubstitutes = $user->getVacancySubstitute();

            $data = $this->get('serializer')->serialize($vacancySubstitutes, 'json', [
                'groups' => ['remplacement']
            ]);
            $response = new Response($data);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à voir cette page"], 500);
        }
    }

    /**
     * @Route("api/remplacement/{id}/edit", name="edit_substitutions_get", methods={"GET"})
     */
    public function editSubstitutionsGet(VacancySubstitute $vacancySubstitute)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user == $vacancySubstitute->getUser()) {

            $data = $this->get('serializer')->serialize($vacancySubstitute, 'json', [
                'groups' => ['remplacement']
            ]);
            $response = new Response($data);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à voir cette page"], 500);
        }
    }

    /**
     * @Route("api/remplacement/{id}/edit", name="edit_substitutions", methods={"POST"})
     */

    public function editRemplacement(VacancySubstitute $vacancySubstitute, Request $request, ValidatorInterface $validator, ObjectManager $manager)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user == $vacancySubstitute->getUser()) {
            $content = $request->getContent();
            $currentVacancySubstitute = $this->get('serializer')->deserialize($content, VacancySubstitute::class, 'json');

            $errors = $validator->validate($currentVacancySubstitute);

            if (count($errors) > 0) {
                dd($errors);
            }

            $startDate = $currentVacancySubstitute->getStartDate();
            $endDate = $currentVacancySubstitute->getEndDate();

            $vacancySubstitute->setStartDate($startDate)
                ->setEndDate($endDate);

            $manager->persist($vacancySubstitute);
            $manager->flush();

            return new JsonResponse("L'absence à bien été édité", 200);
        } else {
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à éditer"], 500);
        }
    }
    /**
     * @Route("api/remplacement/{id}/delete", name="delete_remplacement", methods={"POST"})
     */
    public function delete(VacancySubstitute $vacancySubstitute, ObjectManager $manager)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user == $vacancySubstitute->getUser()) {
            $manager->remove($vacancySubstitute);
            $manager->flush();

            return new JsonResponse('supprimé', 200);
        } else {
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à supprimer"], 500);
        }
    }
}
