<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wishes", name="wish_list", methods={"GET"})
     */
    public function list(WishRepository $wishRepository): Response
    {
        //aller chercher tous les wishes dans la bdd

        $wishes = $wishRepository->findBy(
            ["isPublished" => true],
            ["dateCreated" => "DESC"]
        );

        return $this->render('wish/list.html.twig', [
            "wishes" => $wishes
        ]);
    }

    /**
     * @Route("/wishes/detail/{id}", name="wish_detail", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        //aller chercher la bdd le wish dont l'id est dans l'URL

        $wish = $wishRepository->find($id);

        //Qu'est ce qu'on fait si ce wish n'existe pas en bdd


        return $this->render('wish/detail.html.twig', [
            //passe l'id présent dans l'URL à twig
            "wish_id" => $id,
            "wish" => $wish
        ]);
    }

    /**
     * @Route("/wishes/add", name="wish_add_test")
     */
    public function addTest(EntityManagerInterface $entityManager)
    {
        //Crée une instance de mon entité, vide pour l'instant
       $wish = new Wish();

       //hydrate l'entité
        $wish->setTitle('Faire le tour du monde');
        $wish->setDescription('gregreg');
        $wish->setAuthor('moi');
        $wish->setIsPublished(true);
        $wish->setDateCreated(new \DateTime());

        $entityManager->persist($wish);
        $entityManager->flush();

        return new Response('OK !');
    }

    /**
     * @Route("/wishies/create", name="wishies_create")
     */
    public function create(): Response
    {
        $form = $this->createForm(WishType::class);
        return $this->render('wish/create.html.twig', [
            "wish_form"=>$form->createView()
            ]);
    }
}
