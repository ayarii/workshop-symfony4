<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllController extends AbstractController
{
    /**
     * @Route("/all", name="all")
     */
    public function index(): Response
    {
        return $this->render('all/index.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render("all/about.html.twig");
    }


    /**
     * @Route("/service", name="service")
     */
    public function service(){
        return $this->render("all/service.html.twig");
    }
}
