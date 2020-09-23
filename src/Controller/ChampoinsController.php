<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChampoinsController extends AbstractController
{
    /**
     * @Route("/champoins", name="champoins")
     */
    public function index()
    {
        return $this->render('champoins/index.html.twig', [
            'controller_name' => 'ChampoinsController',
        ]);
    }
}
