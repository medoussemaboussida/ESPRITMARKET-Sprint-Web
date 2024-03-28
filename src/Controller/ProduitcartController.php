<?php

namespace App\Controller;

use App\Entity\Produitcart;
use App\Entity\Panier;
use App\Entity\Utilisateur;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\ProduitcartRepository;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProduitcartController extends AbstractController
{
    #[Route('/produitcart', name: 'app_produitcart')]
    public function index(): Response
    {
        return $this->render('produitcart/index.html.twig', [
            'controller_name' => 'ProduitcartController',
        ]);
    }
    #[Route('/produitcart/supprimer/{id}', name: 'app_produitcart_supprimer')]
    public function supprimer($id,ProduitcartRepository $repository): Response
    {
        $list = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($list);
        $em->flush();
        return $this->redirectToRoute('app_produit_front');
    }
    
    #[Route('/produitcart/{idProduit}/{idUser}', name: 'app_produit_cart')]

    public function ajouter(Request $request, $idProduit, $idUser): Response
    {
        $panier = $this->getDoctrine()->getRepository(Panier::class)->findOneBy(['iduser' => $idUser]);
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($idProduit);

       
        // Créer une nouvelle instance de Produitcart
        $produitCart = new Produitcart();

        // Ajouter le produit au panier
        $produitCart->setIdproduit($produit);
        $produitCart->setIdpanier($panier);

        // Enregistrer l'entité Produitcart
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produitCart);
        $entityManager->flush();

        return $this->redirectToRoute('app_produit_front');
    }

    #[Route('/produitcart/afficher-panier/{idUser}', name: 'afficher_produit_panier')]
    public function afficherPanier($idUser): Response
    {
        // Récupérer le panier de l'utilisateur spécifié
        $panier = $this->getDoctrine()->getRepository(Panier::class)->findOneBy(['iduser' => $idUser]);
        $produitCartRepository = $this->getDoctrine()->getRepository(Produitcart::class);
        $produitsDansPanier = $produitCartRepository->findBy(['idpanier' => $panier]);


        // Afficher les détails du panier
        return $this->render('produitcart/panier.html.twig', [
            'produitsDansPanier' => $produitsDansPanier,
            'panier' => $panier,
        ]);
    }



}
