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
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


/**
 * @Route("/api/garden")
 */
class PlotController extends AbstractController
{
/**
     * @Route("/{gardenid}/plots", name="plots_index", methods={"GET"})
     */
    public function index ($gardenid,PlotRepository $plotRepository, SerializerInterface $serializer, GardenRepository $gardenRepository): Response
    {  
        // $user = $this->get('security.token_storage')->getToken()->getUser();
        // $gardenUser = $this->getUser();

        // if ($user == $gardenUser){
        
        $garden=$gardenRepository->find($gardenid);
        $plots = $plotRepository->findByGarden($garden);
        
        $jsonPlots = $serializer->serialize($plots, 'json',
            ['groups' => 'plot', ]
        );

        return JsonResponse::fromJsonString($jsonPlots);
     
//      return new JsonResponse(["error" => "Vous n'êtes pas autorisé à modifier"], 500);
 }

 /**
     * @Route("/{gardenid}/plots/{id}", name="plot_show", methods={"GET"})
     */
    public function show(Plot $plot, SerializerInterface $serializer, ObjectManager $manager): Response
    {
        // $plotId=$plot->getGarden();
        // $gardenid= $garden->getId();
        // //dd($plotId);

        // if($plotId == $gardenid){        
        $data = $this->get('serializer')->serialize($plot, 'json', ['groups' => ['plot']]);
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    //}
    // else {
    //                 return new JsonResponse(["error" => "Vous n'êtes pas autorisé à éditer"], 500);
    //             }

    }

 /**
     * @Route("/{gardenid}/plots/{id}/edit", name="edit_garden", methods={"PUT"})
     */
    public function edit(Plot $plot, Request $request,  EntityManagerInterface $entityManager,ObjectManager $manager, ValidatorInterface $validator,SerializerInterface $serializer)
    {
            
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
            $status = $editedPlots->getStatus();
            $user= $editedPlots->getUser();
            if ($user != NULL)
            {
                $plot->setUser($user);
                $plot->setStatus('actif');
            }
            
            
            $plot->setUpdatedAt(new \Datetime());
            
            
            $entityManager->merge($plot);
            $entityManager->persist($plot);
            $entityManager->flush();
            return new JsonResponse('message: Votre parcelle a été modifiée', 200);
        
        //return new JsonResponse('message: Vous n\'êtes pas autorisé à modifier cette parcelle', 403);
    }

        /**
         * @IsGranted("ROLE_ADMIN")
     * @Route("/{gardenid}/plots/{id}/delete", name="plot_delete", methods={"DELETE"})
     */
    public function delete(ObjectManager $objectManager,Plot $plot): Response
    {
//         // $user = $this->get('security.token_storage')->getToken()->getUser();
//         // if ($user == $garden->getUser()) {

            $objectManager->remove($plot);
            $objectManager->flush();
            
            return new JsonResponse('message: Votre parcelle a été supprimée', 200);
//     // }
//     //     return new JsonResponse('message: Vous n\'êtes pas autorisé à supprimer cette parcelle', 406);
     }

 }