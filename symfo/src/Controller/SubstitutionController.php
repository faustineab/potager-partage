<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SubstitutionController extends AbstractController
{
    /**
     * @Route("/substitution", name="substitution")
     */
    public function index()
    {
        return $this->render('substitution/index.html.twig', [
            'controller_name' => 'SubstitutionController',
        ]);
    }
}
