<?php

namespace App\Controller;

use App\Entity\Plot;
use App\Entity\User;
use App\Entity\Garden;
use App\Repository\PlotRepository;
use App\Repository\GardenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


/**
 * @Route("/api/garden")
 */
class PlotController extends AbstractController
{
    /**
     * @IsGranted("ROLE_MEMBER")
     * @Route("/{gardenid}/plots", name="plots_index", methods={"GET"})
     * @ParamConverter("garden", options={"id" = "gardenid"})
     */
    public function index ($gardenid,Garden $garden, PlotRepository $plotRepository, SerializerInterface $serializer, GardenRepository $gardenRepository): Response
    {  
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
        
        $gardens=$gardenRepository->find($gardenid);
        $plots = $plotRepository->findByGarden($gardens);
        
        $jsonPlots = $serializer->serialize($plots, 'json',
            ['groups' => 'plot', ]
        );

        return JsonResponse::fromJsonString($jsonPlots);
     
        }else {
            return new JsonResponse(["error" => "Vous n'êtes pas autorisé à entrer dans ce jardin"], 500);
        }
    }

     /**
      * @IsGranted("ROLE_MEMBER")
     * @Route("/{gardenid}/plots/{id}", name="plot_show", methods={"GET"})
     * @ParamConverter("garden", options={"id" = "gardenid"})
     * @ParamConverter("plot", options={"id" = "id"})
     */
    public function show(Plot $plot, Garden $garden,SerializerInterface $serializer, ObjectManager $manager): Response
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
        $data = $this->get('serializer')->serialize($plot, 'json', ['groups' => 'plot',
        'circular_reference_handler' => function ($plot) {
            return $plot->getIsPlantedOns();}
            ]);
        
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    else {
                    return new JsonResponse(["error" => "Vous n'êtes pas autorisé à voir cette parcelle"], 500);
                }

    }

    /**
     * 
     * @Route("/{gardenid}/plots/{id}/edit", name="take_plot", methods={"PUT"})
     * @ParamConverter("garden", options={"id" = "gardenid"})
     * @ParamConverter("plot", options={"id" = "id"})
     */
    public function edit(Plot $plot, Garden $garden,Request $request,  EntityManagerInterface $entityManager,ObjectManager $manager, ValidatorInterface $validator,SerializerInterface $serializer)
    {
        $gardenUsers = $garden->getUsers()->getValues();
        $plotStatus = $plot ->getStatus();
        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare)) && $plotStatus == "inactif") {
            $content = $request->getContent();
            $editedPlots = $serializer->deserialize($content, Plot::class, 'json');
            //dd($editedPlots);
            $errors = $validator->validate($editedPlots);
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
            //dd($currentUser);
            $status = $editedPlots->getStatus();
            //$user= $editedPlots->getUser($currentUser);
            if ($user != NULL)
            {
                $plot->setUser($currentUser);
                $plot->setStatus('actif');
                $plot->setUpdatedAt(new \Datetime());
            }
          
        
            $entityManager->merge($plot);
            $entityManager->persist($plot);
            $entityManager->flush();
            return new JsonResponse('message: Votre parcelle a été modifiée', 200);
        }
        return new JsonResponse('message: Vous n\'êtes pas autorisé à modifier cette parcelle', 403);
    }

/**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{gardenid}/plots/{id}/removeadmin", name="edit_plot_admin", methods={"PUT"})
     * @ParamConverter("garden", options={"id" = "gardenid"})
     * @ParamConverter("plot", options={"id" = "id"})
     */
     public function edit_admin(Plot $plot, Garden $garden,Request $request,  EntityManagerInterface $entityManager,ObjectManager $manager, ValidatorInterface $validator,SerializerInterface $serializer)
     {
         $gardenUsers = $garden->getUsers()->getValues();
 
         $user = [];
         $user[] = $this->get('security.token_storage')->getToken()->getUser();
 
         $compare = function ($user, $gardenUsers) {
             return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
         };
 
         if (!empty(array_uintersect($user, $gardenUsers, $compare))) {
             $content = $request->getContent();
             $editedPlots = $serializer->deserialize($content, Plot::class, 'json');
             //dd($editedPlots);
             $errors = $validator->validate($editedPlots);
             if (count($errors) > 0)
             {
                 foreach ($errors as $error) 
                 {
                     return new JsonResponse(
                         'message: Votre modification comporte des erreurs : '.$error.'.', 
                         304);
                 }
             }

             $plot->setUser(NULL);
             $plot->setStatus('inactif');
             $plot->setUpdatedAt(new \Datetime());
         
             $entityManager->merge($plot);
             $entityManager->persist($plot);
             $entityManager->flush();
             return new JsonResponse('message: Votre parcelle a été modifiée', 200);
         }
         return new JsonResponse('message: Vous n\'êtes pas autorisé à modifier cette parcelle', 403);
     }

    /**
     * @IsGranted("ROLE_MEMBER")
     * @Route("/{gardenid}/plots/{id}/remove", name="user_remove_plot", methods={"PUT"})
     * @ParamConverter("garden", options={"id" = "gardenid"})
     * @ParamConverter("plot", options={"id" = "id"})
     */
    public function removeuser(Plot $plot, Garden $garden,Request $request,  EntityManagerInterface $entityManager, ValidatorInterface $validator,SerializerInterface $serializer): Response
    {
        
        $user= $this->get('security.token_storage')->getToken()->getUser();
        //dd($user);
        $plotUser = $plot->getUser();

        if ($user == $plotUser) {
             $content = $request->getContent();
             $editedPlots = $serializer->deserialize($content, Plot::class, 'json');
             //dd($editedPlots);
             $errors = $validator->validate($editedPlots);
             if (count($errors) > 0)
             {
                 foreach ($errors as $error) 
                 {
                     return new JsonResponse(
                         'message: Votre modification comporte des erreurs : '.$error.'.', 
                         304);
                 }
             }
            //   $status = $editedPlots->getStatus();
              //$user= $editedPlots->getUser(NULL);
            //  if ($user != NULL)
            //  {
                 $plot->setUser(NULL);
                 $plot->setStatus('inactif');
                 $plot->setUpdatedAt(new \Datetime());
            //  }
            $isPlantedOns = $plot->getIsPlantedOns();

            for ($i = 0; $i < count($isPlantedOns); $i++){
                foreach($isPlantedOns as $isPlantedOn){
                    $plot->removeIsPlantedOn($isPlantedOn);
                }
            }
 
         
             $entityManager->merge($plot);
             $entityManager->persist($plot);
             $entityManager->flush();
            
            return new JsonResponse('message: Cette parcelle ne vous est plus attribuée', 200);
    }
        return new JsonResponse('message: Vous n\'êtes pas autorisé à supprimer l\'utilisateur de cette parcelle', 406);
     }
 
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{gardenid}/plots/{id}/delete", name="plot_delete", methods={"DELETE"})
     * @ParamConverter("garden", options={"id" = "gardenid"})
     * @ParamConverter("plot", options={"id" = "id"})
     */
    public function delete(ObjectManager $objectManager,Plot $plot,Garden $garden): Response
    {
        $gardenUsers = $garden->getUsers()->getValues();

        $user = [];
        $user[] = $this->get('security.token_storage')->getToken()->getUser();

        $compare = function ($user, $gardenUsers) {
            return spl_object_hash($user) <=> spl_object_hash($gardenUsers);
        };

        if (!empty(array_uintersect($user, $gardenUsers, $compare))) {

            $objectManager->remove($plot);
            $objectManager->flush();
            
            return new JsonResponse('message: Votre parcelle a été supprimée', 200);
    }
        return new JsonResponse('message: Vous n\'êtes pas autorisé à supprimer cette parcelle', 406);
     }

 }