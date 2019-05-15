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
    public function indexByGarden(Garden $garden, SerializerInterface $serializer)
    {
        $plotsInGarden = $garden->getPlots();
        
        $plotsInGardenJson = $serializer->serialize($plotsInGarden, 'json', [
            'circular_reference_handler' => function ($garden) {
                return $garden->getId();
            },
            'groups' => 'is_planted_on_plots']
        );

        return JsonResponse::fromJsonString($plotsInGardenJson);
    }

    /**
     * @Route("/plot/{id}/plantation", name="plantation_by_plot", methods={"GET"})
     * @ParamConverter("plot", options={"id" = "id"})
     * @IsGranted({"ROLE_MEMBER", "ROLE_ADMIN"})
     */
    public function indexByPlot(Plot $plot, SerializerInterface $serializer)
    {        
        $plot = $serializer->serialize($plot, 'json', [
            'circular_reference_handler' => function ($plot) {
                return $plot->getId();
            },
            'groups' => 'is_planted_on']
        );

        return JsonResponse::fromJsonString($plot);
    }

    /**
     * @Route("/user/{id}/plantation", name="plantation_by_user", methods={"GET"})
     * @ParamConverter("user", options={"id" = "id"})
     * @IsGranted({"ROLE_MEMBER", "ROLE_ADMIN"})
     */
    public function indexByUser(User $user, SerializerInterface $serializer)
    {
        $user = $serializer->serialize($user, 'json', [
            'circular_reference_handler' => function ($user) {
                return $user->getId();
            },
            'groups' => 'is_planted_on']
        );

        return JsonResponse::fromJsonString($user);
    }

    /**
     * @Route("/plot/{plotid}/plantation/new", name="plantation_new", methods={"POST"})
     * @ParamConverter("plot", options={"id" = "plotid"})
     * @IsGranted({"ROLE_MEMBER", "ROLE_ADMIN"})
     */
    public function new(Plot $plot, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager, VegetableRepository $vegetableRepository)
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
    public function show(IsPlantedOn $isPlantedOn, SerializerInterface $serializer)
    {
        $isPlantedOnJson = $serializer->serialize($isPlantedOn, 'json',
            ['groups' => 'is_planted_on']
        );
 
        return JsonResponse::fromJsonString($isPlantedOnJson);
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
