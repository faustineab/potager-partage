<?php

namespace App\Controller;

use App\Entity\User;
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
        $jsonGardens = $serializer->serialize($gardens, 'json',
            ['groups' => 'garden_get']
        );

        return JsonResponse::fromJsonString($jsonGardens);
    }


    /**
     * @IsGranted("ROLE_MEMBER")
     * @Route("/{id}", name="garden_show", methods={"GET"})
     */
    public function show($id,GardenRepository $gardenRepository, SerializerInterface $serializer, Garden $garden): Response
    {
        // $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        // $currentUserArray = [$currentUser];
        // $user = $garden->getUser();
        
        //dd($user);
        $gardenRep = $gardenRepository->find($id);

        // foreach($user as $userId){
        //     //$statut = $userId->getStatut();
        //     //$userArray=[$userId]; 
        // if( empty(array_intersect($currentUserArray, $userId))){ 
        //     throw new HttpException(400, " not valid.");
        // }
            $jsonGarden = $serializer->serialize(
            $gardenRep,
            'json',
            ['groups' => 'garden_get']
        );
    
        return JsonResponse::fromJsonString($jsonGarden);
    // }
    }
    

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/edit", name="edit_garden", methods={"GET"})
     */
    public function edit(Garden $garden, Request $request, ObjectManager $manager, ValidatorInterface $validator)
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
            $data = $this->get('serializer')->serialize($garden, 'json', ['groups' => 'garden_edit']);
            $response = new Response($data);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à modifier"], 500);
        
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/edit", name="edit_garden_post", methods={"POST"})
     */
    public function edit_post(Garden $garden, Request $request, ObjectManager $manager, ValidatorInterface $validator, GardenRepository $gardenRepository)
    {
        // $user = $this->get('security.token_storage')->getToken()->getUser();
        // if ($user == $garden->getUser()) {

            $content = $request->getContent();
            $currentGarden = $this->get('serializer')->deserialize($content, Garden::class, 'json');
         
            $errors = $validator->validate($garden);
            if (count($errors) > 0) {
                dd($errors);
            }
             $name = $currentGarden->getName();
             $address = $currentGarden->getAddress();
            $zipcode = $currentGarden->getZipcode();
            $addressSpecificities = $currentGarden->getAddressSpecificities();
            $gardenMeters = $currentGarden->getMeters();
            $gardenPlots_Row = $currentGarden->getNumberPlotsRow();
            $gardenPlots_Column = $currentGarden->getNumberPlotsColumn();
            $garden->setName($name)
                ->setAddress($address)
                ->setZipcode($zipcode)
                ->setAddressSpecificities($addressSpecificities)
                ->setMeters($gardenMeters)
                ->setNumberPlotsRow($gardenPlots_Row)
                ->setNumberPlotsColumn($gardenPlots_Column);
            //$event->setUser($user);
            $manager->persist($garden);
            $manager->flush();
            return $this->redirectToRoute('garden_show', [
                'id' => $garden->getId(),
            ], Response::HTTP_CREATED);
        // } else {
        //     return new JsonResponse(["error" => "Vous n'êtes pas autorisé à éditer"], 500);
        // }
         
    }

        /**
         * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/delete", name="garden_delete", methods={"DELETE"})
     */
    public function delete(ObjectManager $objectManager, Garden $garden): Response
    {
        // $user = $this->get('security.token_storage')->getToken()->getUser();
        // if ($user == $garden->getUser()) {

            $objectManager->remove($garden);
            $objectManager->flush();
            
            return new JsonResponse('message: Votre jardin a été supprimée', 200);
    // }
    //     return new JsonResponse('message: Vous n\'êtes pas autorisé à supprimer ce jardin', 406);
    }

}