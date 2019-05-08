<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Form\GardenType;
use App\Repository\GardenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/garden")
 */
class GardenController extends AbstractController
{
    /**
     * @Route("/", name="garden_index", methods={"GET"})
     */
    public function index(GardenRepository $gardenRepository): Response
    {
        return $this->render('garden/index.html.twig', [
            'gardens' => $gardenRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="garden_new", methods={"GET","POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        // On doit créer un Department à partir de la requête reçue
        // Objectif : récupérer le corps de la requête (JSON)
        // puis le convertir en entité Doctrine pour le sauvegarder
        $content = $request->getContent();
        // Désérialisons le JSON
        $garden = $serializer->deserialize($content, Garden::class, 'json');
        // Utilisons le validator directement
        // cf : https://symfony.com/doc/current/validation.html
        // car le form n'est plus là pour le faire à notre place
        $errors = $validator->validate($garden);
        // si erreurs ($errors étant un tableau d'erreurs)
        if (count($errors) > 0) {
            dd($errors);
            // @TODO : boucler sur la liste d'erreurs et la renvoyer en JSON
            // + le bon HTTP status code 
        }
        // Pas d'erreurs
        $manager->persist($garden);
        $manager->flush();
        return $this->redirectToRoute('garden_show', [
            'id' => $garden->getId(),
        ], Response::HTTP_CREATED); // Response::HTTP_CREATED = 201
    }


    /**
     * @Route("/{id}/edit", name="garden_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Garden $garden): Response
    {
        $form = $this->createForm(GardenType::class, $garden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('garden_index', [
                'id' => $garden->getId(),
            ]);
        }

        return $this->render('garden/edit.html.twig', [
            'garden' => $garden,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="garden_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Garden $garden): Response
    {
        if ($this->isCsrfTokenValid('delete' . $garden->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($garden);
            $entityManager->flush();
        }

        return $this->redirectToRoute('garden_index');
    }
}
