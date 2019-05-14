<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/plantation", name="plantation")
 */
class IsPlantedOnController extends AbstractController
{
    /**
     * @Route("/garden/{id}", name="plantation_by_garden")
     */
    public function indexByGarden()
    {
        
    }

    /**
     * @Route("/garden/{gardenid}/plot/{id}", name="plantation_by_plot")
     */
    public function indexByPlot()
    {
        
    }

    /**
     * @Route("/garden/{gardenid}/user/{id}", name="plantation_by_user")
     */
    public function indexByUser()
    {
        
    }

    /**
     * @Route("/garden/{gardenid}/plot/{plotid}/new", name="plantation_new")
     */
    public function new()
    {
        
    }

    /**
     * @Route("/{id}", name="plantation_show")
     */
    public function show()
    {
        
    }

    /**
     * @Route("/{id}/edit", name="plantation_edit")
     */
    public function edit()
    {
        
    }

    /**
     * @Route("/{id}", name="plantation_delete")
     */
    public function delete()
    {
        
    }
}
