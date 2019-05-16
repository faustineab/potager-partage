<?php

namespace App\Controller;


use App\Repository\VegetableRepository;
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
use App\Entity\Vegetable;



/**
 * @Route("/api/vegetable")
 */
class VegetableController extends AbstractController
{
/**
     * @Route("/", name="vegetable_index", methods={"GET"})
     */
    public function index(VegetableRepository $vegetableRepository, SerializerInterface $serializer): Response

    {
        $vegetables = $vegetableRepository->findAll();
        $jsonVegetables = $serializer->serialize($vegetables, 'json',
            ['groups' => 'vegetable']
        );

        return JsonResponse::fromJsonString($jsonVegetables);
    }

    /**
     * @Route("/{id}", name="vegetable_show", methods={"GET"})
     */
    public function show($id,VegetableRepository $vegetableRepository, SerializerInterface $serializer): Response
    {
        
        $vegetableRep = $vegetableRepository->find($id);

            $jsonVegetable = $serializer->serialize(
            $vegetableRep,
            'json',
            ['groups' => 'vegetable']
        );
    
        return JsonResponse::fromJsonString($jsonVegetable);
    }

        /**
     * @Route("/new", name="vegetable_new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $content = $request->getContent();
        
        $vegetable = $serializer->deserialize($content, Vegetable::class, 'json');
        
        $errors = $validator->validate($vegetable);
        if (count($errors) > 0)
            {
                foreach ($errors as $error) 
                {
                    return JsonResponse::fromJsonString(
                        'message: Votre affichage comporte des erreurs : '.$error.'.', 
                        406);
                }
            }
        
        $entityManager->persist($vegetable);
        $entityManager->flush();
        
        return $this->redirectToRoute('vegetable_show', [
            'id' => $vegetable->getId(),
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}/edit", name="edit_vegetable", methods={"PUT"})
     */
    public function edit(Vegetable $vegetable, Request $request,  EntityManagerInterface $entityManager,ObjectManager $manager, ValidatorInterface $validator,SerializerInterface $serializer)
    {
    
            $content = $request->getContent();
            $editedVegetable = $serializer->deserialize($content, Vegetable::class, 'json');
            //dd($editedVegetable);
            $errors = $validator->validate($editedVegetable);
            if (count($errors) > 0)
            {
                foreach ($errors as $error) 
                {
                    return new JsonResponse(
                        'message: Votre modification comporte des erreurs : '.$error.'.', 
                        304);
                }
            }
            $name = $editedVegetable->getName();
            $water_irrigation_interval= $editedVegetable->getWaterIrrigationInterval();
            $growing_interval=$editedVegetable->getGrowingInterval();
            $image = $editedVegetable->getImage();
            
            if ($name != Null){
            $vegetable->setName($name);
            }
            if($water_irrigation_interval!= Null){
            $vegetable->setWaterIrrigationInterval($water_irrigation_interval);
            }
            if($growing_interval!= Null){
            $vegetable->setGrowingInterval($growing_interval);
            }
            if($image!=Null){
            $vegetable->setImage($image);
            }
            $vegetable->setUpdatedAt(new \Datetime());
        
            $entityManager->merge($vegetable);
            $entityManager->persist($vegetable);
            $entityManager->flush();
            return new JsonResponse('message: Votre fruit/légume a été modifié', 200);
        }


}