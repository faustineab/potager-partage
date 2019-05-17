<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\Plot;
use App\Entity\User;
use App\Entity\Garden;
use App\Entity\Vegetable;
use App\Entity\IsPlantedOn;
use Doctrine\ORM\EntityManager;
use App\Repository\GardenRepository;
use App\Repository\VegetableRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\IsPlantedOnRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\PlotRepository;

/**
 * @Route("api/garden/{gardenid}")
 * @ParamConverter("garden", options={"id" = "gardenid"})
 */
class IsPlantedOnController extends AbstractController
{
    /**
     * @Route("/plantation", name="plantation_by_garden", methods={"GET"})
     * @IsGranted({"ROLE_MEMBER", "ROLE_ADMIN"})
     */
    public function indexByGarden(Garden $garden, SerializerInterface $serializer, IsPlantedOnRepository $isPlantedOnRepository, PlotRepository $plotRepository, GardenRepository $gardenrepository)
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
        
            $plots = $isPlantedOnRepository->findAll();
            
            $jsonPlots = $serializer->serialize($plots, 'json',
                ['groups' => 'is_planted_on', ]
            );

            return JsonResponse::fromJsonString($jsonPlots);
    }
    return new JsonResponse(["error" => "Vous n'êtes pas autorisé à aller sur cette page."], 500);
    }

    /**
     * @Route("/plots/{id}/plantation", name="plantation_by_plot", methods={"GET"})
     * @ParamConverter("plot", options={"id" = "id"})
     * @IsGranted({"ROLE_MEMBER", "ROLE_ADMIN"})
     */
    public function indexByPlot($id,Garden $garden,Plot $plot, IsPlantedOnRepository $isPlantedOnRepository, PlotRepository $plotRepository, SerializerInterface $serializer)
    {        
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
            $plots= $plotRepository->find($id);
            $plantations = $isPlantedOnRepository->findByPlot($plots);
            
            $jsonPlantations = $serializer->serialize($plantations, 'json',
                ['groups' => 'is_planted_on', ]
            );

            return JsonResponse::fromJsonString($jsonPlantations);
    }
    return new JsonResponse(["error" => "Vous n'êtes pas autorisé à aller sur cette page."], 500);
    }

    /**
     * @Route("/user/{userid}/plantation", name="plantation_by_user", methods={"GET"})
     * @ParamConverter("user", options={"id" = "userid"})
     * @IsGranted({"ROLE_MEMBER", "ROLE_ADMIN"})
     */
    public function indexByUser($userid,User $user, SerializerInterface $serializer,Garden $garden, IsPlantedOnRepository $isPlantedOnRepository, PlotRepository $plotRepository, GardenRepository $gardenrepository)
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
            $users= $plotRepository->findByUser($userid);
            $plots = $isPlantedOnRepository->findByPlot($users);
            
            $jsonPlots = $serializer->serialize($plots, 'json',
                ['groups' => 'is_planted_on', ]
            );

            return JsonResponse::fromJsonString($jsonPlots);
    
    }
    return new JsonResponse(["error" => "Vous n'êtes pas autorisé à aller sur cette page."], 500);
    }

    /**
     * @Route("/plots/{id}/plantation/new", name="plantation_new", methods={"POST"})
     * @ParamConverter("plot", options={"id" = "id"})
     * @IsGranted({"ROLE_MEMBER", "ROLE_ADMIN"})
     */
    public function new(Plot $plot, Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, VegetableRepository $vegetableRepository)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        
        //$plot = $isPlantedOn->getPlot();
        $plotOwner = $plot->getUser();

        if ($currentUser == $plotOwner) {
            $isPlantedOn = new IsPlantedOn();
            
            $data = json_decode($request->getContent(), true);
            // dd($data);
            
            
            $errors = $validator->validate($data);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    return JsonResponse::fromJsonString(
                        'message: Votre entrée comporte des erreurs : '.$error.'.',
                        406
                    );
                }
            }
        
                
                // informations récupérées du formulaire
                $getVegetable = $vegetableRepository->findOneBy([
                    'id' => $data['vegetable']
                    ]);
                    //dd($getVegetable);
                    
                    $seedlingDate = new DateTime($data['seedling_date']);
                    // dd($seedling_date);
                    
                    // informations settées automatiquement
                    // irrigation_date  
                    $durationI = $getVegetable->getWaterIrrigationInterval(); //durée à rajouter en jours
                    $durationI = DateInterval::createFromDateString($durationI.' day'); // transforme integer duration en date interval
                    $irrigationDate = new Datetime($data['seedling_date']); 
                    $irrigationDate = $irrigationDate->add($durationI);// ajoute duration à date de seedling
                    
                    //harvestDate
                    $durationH = $getVegetable->getGrowingInterval();
                    $durationH = DateInterval::createFromDateString($durationH.' week');
                    $harvestDate = new Datetime($data['seedling_date']);
                    $harvestDate = $harvestDate->add($durationH);
                    // dd($harvestDate);
                    
                    
                    $isPlantedOn->setSeedlingDate($seedlingDate)
                    ->setIrrigationDate($irrigationDate)
                    ->setPlot($plot)
                    ->setHarvestDate($harvestDate)
                    ->setVegetable($getVegetable);
                    
                    $entityManager->persist($isPlantedOn);
                    $entityManager->flush();
                    
                    return JsonResponse::fromJsonString('ok', 200);
        }
                
        return JsonResponse::fromJsonString('nope', 400);
    }

    /**
     * @Route("/plantation/{id}", name="plantation_show", methods={"GET"})
     * @IsGranted({"ROLE_MEMBER", "ROLE_ADMIN"})
     */
    public function show(IsPlantedOn $isPlantedOn, Garden $garden,SerializerInterface $serializer)
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
            $isPlantedOnJson = $serializer->serialize($isPlantedOn, 'json',
            ['groups' => 'is_planted_on']
        );
 
        return JsonResponse::fromJsonString($isPlantedOnJson);
    }
    return new JsonResponse(["error" => "Vous n'êtes pas autorisé à aller sur cette page."], 500);
    }

    /**
     * @Route("/plantation/{id}/edit", name="plantation_edit", methods={"PUT"})
     * @IsGranted({"ROLE_MEMBER", "ROLE_ADMIN"})
     */
    public function edit(IsPlantedOn $isPlantedOn, ObjectManager $manager)
    {
        $vegetable = $isPlantedOn->getVegetable();
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        
        $plot = $isPlantedOn->getPlot();
        $plotOwner = $plot->getUser();

        // Si le statut arrosage = undone
        if ($isPlantedOn->getSprayStatus() == false && $currentUser == $plotOwner) {
            
            // passer le statut à done
            $isPlantedOn->setSprayStatus(true);

            // modifier la prochaine date d'irrigation
            $durationI = $vegetable->getWaterIrrigationInterval(); //durée à rajouter en jours
            $durationI = DateInterval::createFromDateString($durationI.' day'); // transforme integer duration en date interval
            $irrigationDate = new Datetime('now'); 
            $irrigationDate = $irrigationDate->add($durationI);
            $isPlantedOn->setIrrigationDate($irrigationDate);

            // puis repasser en arrosage = undone
            $isPlantedOn->setSprayStatus(false);

            $manager->merge($isPlantedOn);
            $manager->persist($isPlantedOn);
            $manager->flush();

            return JsonResponse::fromJsonString('ok', 200);
        }
        
        return JsonResponse::fromJsonString('nope', 400);
    }

    /**
     * @Route("/plantation/{id}", name="plantation_delete", methods={"DELETE"})
     * @IsGranted({"ROLE_MEMBER", "ROLE_ADMIN"})
     */
    public function delete(IsPlantedOn $isPlantedOn, ObjectManager $objectManager)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        
        $plot = $isPlantedOn->getPlot();
        $plotOwner = $plot->getUser();

        if ($currentUser == $plotOwner) 
        {
            $objectManager->remove($isPlantedOn);
            $objectManager->flush();
            
            return JsonResponse::fromJsonString('ok', 200);
        }

        return JsonResponse::fromJsonString('nope', 400);
    }
}
