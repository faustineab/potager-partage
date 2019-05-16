<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Plot;
use App\Entity\Garden;
use App\Repository\GardenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Repository\PlotRepository;

/**
 * @Route("/api/garden")
 */
class GardenController extends AbstractController
{
    /**
     * @Route("/", name="garden_index", methods={"GET"})
     */
    public function index(GardenRepository $gardenRepository, SerializerInterface $serializer): Response

    {
        $gardens = $gardenRepository->findAll();
        $jsonGardens = $serializer->serialize(
            $gardens,
            'json',
            ['groups' => 'garden_get']
        );

        return JsonResponse::fromJsonString($jsonGardens);
    }


    /**
     * @IsGranted("ROLE_MEMBER")
     * @Route("/{id}", name="garden_show", methods={"GET"})
     */
    public function show($id, GardenRepository $gardenRepository, SerializerInterface $serializer, Garden $garden): Response
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
            $gardenRep = $gardenRepository->find($id);

            $jsonGarden = $serializer->serialize(
                $gardenRep,
                'json',
                ['groups' => 'garden_get']
            );

            return JsonResponse::fromJsonString($jsonGarden);
        }
        return new JsonResponse(["error" => "Vous n'êtes pas autorisé à rentrer dans ce jardin"], 500);
    }


    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/edit", name="edit_garden", methods={"PUT"})
     */
    public function edit(Garden $garden, Request $request, ObjectManager $manager, ValidatorInterface $validator, PlotRepository $plotRepository, SerializerInterface $serializer)
    {
        $content = $request->getContent();
        $editedGarden = $serializer->deserialize($content, Garden::class, 'json');

        $errors = $validator->validate($editedGarden);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                return JsonResponse::fromJsonString('Votre entrée comporte des erreurs :' . $error . '.', 406);
            }
        }

        $editedName = $editedGarden->getName();
        if ($editedName != null) {
            $garden->setName($editedName);
        }
        $editedAddress = $editedGarden->getAddress();
        if ($editedAddress != null) {
            $garden->setAddress($editedAddress);
        }
        $editedZipcode = $editedGarden->getZipcode();
        if ($editedZipcode != null) {
            $garden->setZipcode($editedZipcode);
        }
        $editedCity = $editedGarden->getCity();
        if ($editedCity != null) {
            $garden->setCity($editedCity);
        }
        $editedAddressSpecifities = $editedGarden->getAddressSpecificities();
        if ($editedAddressSpecifities != null) {
            $garden->setAddressSpecificities($editedAddressSpecifities);
        }
        $editedMeters = $editedGarden->getMeters();
        if ($editedMeters != null) {
            $garden->setMeters($editedMeters);
        }

        $garden->setUpdatedAt(new \Datetime());

        $manager->merge($garden);
        $manager->persist($garden);
        $manager->flush();


        $oldPlotCount = count($garden->getPlots());
        // dump($oldPlotCount);
        $newPlotCount = $editedGarden->getNumberPlotsColumn() * $editedGarden->getNumberPlotsRow();
        // dd($newPlotCount);
        if ($oldPlotCount > $newPlotCount) {
            $plotCountToRemove = $oldPlotCount - $newPlotCount;

            $inactivePlots = $plotRepository->findBy([
                'garden' => $garden,
                'status' => 'inactif'
            ]);

            if ($plotCountToRemove > count($inactivePlots) || empty($inactivePlots)) {
                return new JsonResponse("Vous n'avez pas assez de parcelles inactives pour modifier");
            }


            $editedNumberPlotsColumn = $editedGarden->getNumberPlotsColumn();
            if ($editedNumberPlotsColumn != null) {
                $garden->setNumberPlotsColumn($editedNumberPlotsColumn);
            }
            $editedNumberPlotsRow = $editedGarden->getNumberPlotsRow();
            if ($editedNumberPlotsRow != null) {
                $garden->setNumberPlotsRow($editedNumberPlotsRow);
            }

            $manager->merge($garden);
            $manager->persist($garden);
            $manager->flush();


            for ($i = 0; $i < $plotCountToRemove; $i++) {
                $plotToRemove = $plotRepository->findOneBy([
                    'garden' => $garden,
                    'status' => 'inactif'
                ]);

                $garden->removePlot($plotToRemove);
                $manager->merge($garden);
                $manager->persist($garden);
                $manager->flush();
            }
        } elseif ($oldPlotCount < $newPlotCount) {
            $editedNumberPlotsColumn = $editedGarden->getNumberPlotsColumn();
            if ($editedNumberPlotsColumn != null) {
                $garden->setNumberPlotsColumn($editedNumberPlotsColumn);
            }
            $editedNumberPlotsRow = $editedGarden->getNumberPlotsRow();
            if ($editedNumberPlotsRow != null) {
                $garden->setNumberPlotsRow($editedNumberPlotsRow);
            }
            $manager->merge($garden);
            $manager->persist($garden);
            $manager->flush();

            $plotCountToCreate = $newPlotCount - $oldPlotCount;
            for ($i = 0; $i < $plotCountToCreate; $i++) {
                $newPlot = new Plot();
                $newPlot->setStatus('inactif')
                    ->setGarden($garden);
                $manager->persist($newPlot);
                $manager->flush();
            }
        }

        return JsonResponse::fromJsonString('ok', 200);
    }


    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/delete", name="garden_delete", methods={"DELETE"})
     */
    public function delete(ObjectManager $objectManager, Garden $garden): Response
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {

            foreach ($garden->getUsers() as $gardenUser) {

                $garden->removeUser($gardenUser);
                $objectManager->persist($garden);
            }

            $objectManager->flush();
            $objectManager->remove($garden);
            $objectManager->flush();

            return new JsonResponse('message: Votre jardin a été supprimée', 200);
        }
        return new JsonResponse('message: Vous n\'êtes pas autorisé à supprimer ce jardin', 406);
    }
}
