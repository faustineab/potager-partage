<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Form\GardenType;
use App\Repository\GardenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use JMS\Serializer\SerializationContext;

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
     * @Route("/{id}", name="garden_show", methods={"GET"})
     */
    public function show($id,GardenRepository $gardenRepository,SerializerInterface $serializer): Response
    {
        $garden = $gardenRepository->find($id);
        $jsonGarden = $serializer->serialize(
            $garden,
            'json',
            ['groups' => 'garden_get']
        );
        return JsonResponse::fromJsonString($jsonGarden);
    }
/**
     * @Route("/{id}", name="garden_delete", methods={"DELETE"})
     */
    public function delete($id,Request $request, Garden $garden, SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('delete'.$garden->getId(), $request->request->get('_token'))) {
   
        }

        $manager->remove($garden);
        $manager->flush();

        return $this->redirectToRoute('garden_index', [
            'id' => $garden->getId(),
        ], Response::HTTP_OK);
    }

}