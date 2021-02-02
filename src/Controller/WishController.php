<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/wishes/create", name="wish_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        //crée l'instance vide associée au formulaire
        $wish = new Wish();

        //retourne l'entité User de l'utilisateur connecté
        $user = $this->getUser();
        //préremplit le champ Author
        $wish->setAuthor( $user->getUsername());

        //on hydrate les propriétés manquantes
        $wish->setDateCreated(new \DateTime());
        $wish->setIsPublished(true);

        //crée le formulaire en lui passant l'instance vide
        //le dernier argument permet au besoin de passer des données à la construction du form
        $wishForm = $this->createForm(WishType::class, $wish, ['btn_text' => 'yoyoyo']);

        //récupère les données du form est les injecte dans le wish
        $wishForm->handleRequest($request);

        //si le form est soumis et valide
        if ($wishForm->isSubmitted() && $wishForm->isValid()){
            //on insert
            $entityManager->persist($wish);
            $entityManager->flush();

            //crée un message en session pour l'afficher sur la prochaine page
            $this->addFlash('success', 'Your idea has been created!');

            //redirige vers une autre page
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/create.html.twig', [
            "wish_form" => $wishForm->createView() //passe le form à twig
        ]);
    }

    /**
     * @Route("/wishes", name="wish_list")
     */
    public function list(WishRepository $wishRepository): Response
    {
        //aller chercher tous les wishes dans la bdd
        //$wishRepository = $this->getDoctrine()->getRepository(Wish::class);
        $wishes = $wishRepository->findCategorizedWishes();

        return $this->render('wish/list.html.twig', [
            "wishes" => $wishes
        ]);
    }

    /**
     * @Route("/wishes/detail/{id}", name="wish_detail", requirements={"id": "\d+"})
     */
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        //aller chercher dans la bdd le wish dont l'id est dans l'URL
        $wish = $wishRepository->find($id);

        //qu'est-ce qu'on fait si ce wish n'existe pas en bdd
        if (!$wish){
            //alors on déclenche une 404
            throw $this->createNotFoundException('This wish is gone.');
        }

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
        //crée une instance de mon entité, vide pour l'instant
        $wish = new Wish();

        //hydrate l'entité
        $wish->setTitle('Aller en Inde');
        $wish->setDescription('djf akdlfjkl fjlsfj kl');
        $wish->setAuthor('moi');
        $wish->setIsPublished(true);
        $wish->setDateCreated(new \DateTime());

        $entityManager->persist($wish);
        $entityManager->flush();

        return new Response('ok !');
    }

}