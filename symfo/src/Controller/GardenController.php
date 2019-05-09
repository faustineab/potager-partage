<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Garden;
use App\Repository\GardenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function show($id,GardenRepository $gardenRepository,SerializerInterface $serializer, Garden $gardens): Response
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $user = $gardens->getUser();
        $statut = $currentUser->getStatut();
        $garden = $gardenRepository->find($id);

        if( $currentUser!= $user && $statut != "ok"){ 
            throw new HttpException(400, " not valid.");
        }
            $jsonGarden = $serializer->serialize(
            $garden,
            'json',
            ['groups' => 'garden_get']
        );
    
        return JsonResponse::fromJsonString($jsonGarden);
    
    }

    /**
     * @IsGranted("ROLE_ADMIN")
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