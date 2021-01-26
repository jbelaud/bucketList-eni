<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wishes", name="wish_list", methods={"GET"})
     */
    public function list(): Response
    {
        //@todo: aller chercher tous les wishes dans la bdd

        return $this->render('wish/list.html.twig',[]);
    }

    /**
     * @Route("/wishes/detail/{id}", name="wish_detail", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function detail(int $id): Response
    {
        //@todo: aller chercher la bdd le wish dont l'id est dans l'URL

        return $this->render('wish/detail.html.twig', [
            //passe l'id présent dans l'URL à twig
            "wish_id" => $id
        ]);
    }
}
