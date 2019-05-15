<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Entity\Vegetable;
use App\Entity\MarketOffer;
use App\Repository\VegetableRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MarketOfferRepository;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



/**
 * @Route("/api/garden")
 */
class MarketOfferController extends AbstractController
{
    /**
     * @Route("/{gardenid}/marketoffer", name="market_offer_index", methods={"GET"})
     * @ParamConverter("garden", options={"id" = "gardenid"})
     */
    public function index(MarketOfferRepository $marketOfferRepository, Garden $garden, SerializerInterface $serializer): Response

    {
        $gardenUsers = $garden->getUsers()->getValues();
 
        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {

        $marketOffers = $marketOfferRepository->findAll();
        $jsonMarketOffers = $serializer->serialize($marketOffers, 'json',
            ['groups' => 'marketoffer']
        );

        return JsonResponse::fromJsonString($jsonMarketOffers);
    }else {
                     return new JsonResponse(["error" => "Vous n'êtes pas autorisé à accéder à cette page."], 500);
                 }
    }
     /**
     * @IsGranted("ROLE_MEMBER")
     * @Route("/{gardenid}/marketoffer/{id}", name="market_offer_show", methods={"GET"})
     * @ParamConverter("garden", options={"id" = "gardenid"})
     * @ParamConverter("market_offer", options={"id" = "id"})
     */
     public function show(MarketOffer $marketOffer, Garden $garden,SerializerInterface $serializer, ObjectManager $manager): Response
     {
         $gardenUsers = $garden->getUsers()->getValues();
 
         $user = [];
         $user[] = $this->get('security.token_storage')->getToken()->getUser();
 
         $compare = function ($user, $gardenUsers) {
             return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
         };
 
         if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
         
         $data = $this->get('serializer')->serialize($marketOffer, 'json', ['groups' => ['marketoffer']]);
         $response = new Response($data);
         $response->headers->set('Content-Type', 'application/json');
         return $response;
     }
     else {
                     return new JsonResponse(["error" => "Vous n'êtes pas autorisé à accéder à cette page."], 500);
                 }
 
     }

    /**
     * @Route("/{gardenid}/marketoffer/new", name="market_offer_new", methods={"POST"})
     * @ParamConverter("garden", options={"id" = "gardenid"})
     */
     public function new($gardenid, Request $request, Garden $garden, VegetableRepository $vegetableRepository, MarketOffer $marketOffer , SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
     {
        $content = $request->getContent();
         
         $marketOffer = $serializer->deserialize($content, MarketOffer::class, 'json');
         
         $errors = $validator->validate($marketOffer);
         if (count($errors) > 0)
             {
                 foreach ($errors as $error) 
                 {
                     return JsonResponse::fromJsonString(
                         'message: Votre affichage comporte des erreurs : '.$error.'.', 
                         406);
                 }
             }
             $currentUser= $this->get('security.token_storage')->getToken()->getUser();
             $currentGarden = $garden;
             $currentVegetable = $vegetableRepository->findOneByName(['name'=> $content['vegetable']
             ]);
             //dd($currentVegetable);
    
           
             $marketOffer->setUser($currentUser);
             $marketOffer->setGarden($currentGarden);
             $marketOffer->setVegetable($currentVegetable);
             $marketOffer->setCreatedAt(new \Datetime());
             
         $entityManager->persist($marketOffer);
         $entityManager->flush();
         
         return new JsonResponse('message: Votre offre a été créé', 200);
     }

         /**
     * @IsGranted("ROLE_MEMBER")
     * @Route("/{gardenid}/marketoffer/{id}/edit", name="market_offer_edit", methods={"PUT"})
     * @ParamConverter("garden", options={"id" = "gardenid"})
     * @ParamConverter("market_offer", options={"id" = "id"})
     */
    public function edit(MarketOffer $marketOffer, Garden $garden,Request $request,  EntityManagerInterface $entityManager,ObjectManager $manager, ValidatorInterface $validator,SerializerInterface $serializer)
    {
        $marketOfferUser = $marketOffer->getUser();
        $Currentuser = $this->get('security.token_storage')->getToken()->getUser();

        if ($Currentuser == $gardenUsers) {

            $content = $request->getContent();
            $editedMarketOffer = $serializer->deserialize($content, MarketOffer::class, 'json');
            //dd($editedMarketOffer);
            $errors = $validator->validate($editedMarketOffer);
            if (count($errors) > 0)
            {
                foreach ($errors as $error) 
                {
                    return new JsonResponse(
                        'message: Votre modification comporte des erreurs : '.$error.'.', 
                        304);
                }
            }
            $currentUser= $this->get('security.token_storage')->getToken()->getUser();
            $currentGarden = $garden;
            //dd($currentUser);
            $currentVegetable = $editedMarketOffer->getVegetable();
            
            $marketOffer->setUser($currentUser);
            $marketOffer->setGarden($currentGarden);
            $marketOffer->setUpdatedAt(new \Datetime());

            if ($user != NULL)
            {
                $marketOffer->setUser($currentUser);
            }
          
        
            $entityManager->merge($marketOffer);
            $entityManager->persist($marketOffer);
            $entityManager->flush();
            return new JsonResponse('message: Votre offre a été modifiée', 200);
        }
        return new JsonResponse('message: Vous n\'êtes pas autorisé à modifier cette page', 403);
    }


}