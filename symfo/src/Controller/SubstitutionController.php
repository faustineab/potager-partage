<?php

namespace App\Controller;

use App\Entity\Vacancy;
use App\Form\SubstitutionType;
use App\Entity\VacancySubstitute;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
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
        // dump($substitution);

        $availableDates = $vacancy->getAvailableDays();
        // dump($availableDates);

        // foreach ($availableDates as $availableDate) {
        //     $availableDates = [];
        //     $availableDates[] = $availableDate->format('Y-m-d H:i:s');
        // }

        // dump($substitution->getDays());
        // exit;
        $substitutionDates = $substitution->getDays();

        dump($substitutionDates);


        dump($availableDates);

        // foreach ($substitutionDates as $substitutionDate) {
        // if (array_search($substitutionDate, $availableDates) !== null) 


        if (!empty(array_diff($substitutionDates, $availableDates)))

        //  !== null) 
        {
            return new JsonResponse("Les dates choisies ne correspondent pas aux dates d' absences", 500);
            // dump($substitutionDate);
        }
        if ($substitution->isBookableDate()) {
            // dump($substitution->isBookableDate());
            // exit;
            $substitution->setUser($user);

            $manager->persist($substitution);
            $manager->flush();
            return new JsonResponse("L'absence à bien été créée", 200);
        }
        return new JsonResponse('les dates choisies ont déja été réservées', 500);
    }
}
