<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BondecommandeController extends AbstractController
{
    /**
     * @Route("/bondecommande", name="bondecommande")
     */
    public function index(): Response
    {
        return $this->render('bondecommande/index.html.twig', [
            'controller_name' => 'BondecommandeController',
        ]);
    }
}
