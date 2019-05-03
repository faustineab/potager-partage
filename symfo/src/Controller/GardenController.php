<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Form\GardenType;
use App\Repository\GardenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/potager/add", name="garden_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $garden = new Garden();
        $user = $this->getUser();
        $form = $this->createForm(GardenType::class, $garden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(array('ROLE_ADMIN'));
            $garden->addUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($garden);
            $entityManager->flush();

            return $this->redirectToRoute('garden_index');
        }

        return $this->render('garden/new.html.twig', [
            'garden' => $garden,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="garden_show", methods={"GET"})
     */
    public function show(Garden $garden): Response
    {
        return $this->render('garden/show.html.twig', [
            'garden' => $garden,
        ]);
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
        if ($this->isCsrfTokenValid('delete'.$garden->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($garden);
            $entityManager->flush();
        }

        return $this->redirectToRoute('garden_index');
    }
}
