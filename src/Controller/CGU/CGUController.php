<?php

namespace App\Controller\CGU;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @route("/cgu")
 */

class CGUController extends AbstractController
{
    /**
     * @Route("/conditions-generales-utilisation", name="cgu_conditions")
     */
    public function index(): Response
    {
        return $this->render('cgu/index.html.twig', [
            'controller_name' => 'CGUController',
        ]);
    }
}
