<?php

namespace App\Controller;

use App\Entity\User;
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

class SubstitutionController extends AbstractController
{
    /**
     * @Route("api/absence/{id}/remplacement", name="create_substitution", methods={"GET","POST"})
     */
    public function substitution(Request $request, ObjectManager $manager, Vacancy $vacancy, ValidatorInterface $validator)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();

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

            $substitution->setUser($user);

            $manager->persist($substitution);
            $manager->flush();
            return new JsonResponse("L'absence à bien été créée", 200);
        }
        return new JsonResponse('les dates choisies ont déja été réservées', 500);
    }
    /**
     * @Route("api/absence/{id}/remplacements", name="show_substitutions", methods={"GET"})
     */
    public function showSubstitutions(Vacancy $vacancy)
    {

        $substitutions = $this->get('serializer')->serialize($vacancy, 'json', ['groups' => ['vacancy']]);


        $response = new Response($substitutions);

        return $response;
    }

    /**
     * @Route("api/user/{id}/remplacements", name="show_substitutions", methods={"GET"})
     */
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
}
