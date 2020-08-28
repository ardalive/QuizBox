<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TwigListController extends AbstractController
{
    /**
     * @Route("/list", name="twig_list")
     */
    public function index()
    {
        date_default_timezone_set('Europe/Moscow');
        $list = array_fill(0, 20, 'banana');
        $date = time();
        return $this->render('twig_list/index.html.twig', [
            'list' => $list,
            'date' => $date,
        ]);
    }
}
