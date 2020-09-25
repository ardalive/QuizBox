<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChampionsController extends AbstractController
{
    /**
     * @Route("/champions", name="champions")
     */
    public function index()
    {
        return $this->render('champions/index.html.twig', [
            'controller_name' => 'ChampionsController',
        ]);
    }
}
