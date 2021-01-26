<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home", methods={"GET"})
     */
    public function home(): Response
    {

        return $this->render('main/home.html.twig', []);
    }


    /**
     * @Route("/about-us", name="main_about_us", methods={"GET", "POST"})
     */
    public function aboutUs(): Response
    {

        return $this->render('main/about_us.html.twig', []);
    }
}