<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produitcart;
use App\Entity\Panier;
use App\Entity\Utilisateur;
use App\Entity\Produit;
use App\Repository\ProduitcartRepository;
use App\Repository\PanierRepository;
use App\Form\CodePromoType;
use App\Entity\Codepromo;
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
     //ajouter meme produit avec bouton +
     #[Route('/produitcart/ajouterPlus/{id}/{idp}', name: 'app_produitcart_ajouter')]
     public function ajouterP($id,$idp, ProduitcartRepository $repository, PanierRepository $panierRepository): Response
     {
         
         $panier = $panierRepository->find($idp);
         $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);
 
         $nouveauProduit = new Produitcart();
 
         // Ajouter le produit au panier
         $nouveauProduit->setIdproduit($produit);
         $nouveauProduit ->setIdpanier($panier);
 
 
     // Enregistrer le nouveau produit dans la base de données
     $entityManager = $this->getDoctrine()->getManager();
     $entityManager->persist($nouveauProduit);
     $entityManager->flush();
 
     // Rediriger l'utilisateur vers la page du panier
     return $this->redirectToRoute('app_produit_front');
     }
 
 
  
    //supprimer un produit de votre panier avec bouton -
    #[Route('/produitcart/supprimer/{id}', name: 'app_produitcart_supprimer')]
    public function supprimer($id,ProduitcartRepository $repository): Response
    {
      //recherche et suppression aleatoire de produit se repete plusieurs fois
        $produits = $repository->findBy(['idproduit' => $id]);
        $produit = $produits[array_rand($produits)];
 
        $em = $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('app_produit_front');
    }
    
  //ajouter un produit a votre panier
  #[Route('/produitcart/ajouter/{idProduit}/{idUser}', name: 'app_produit_cart')]
 
  public function ajouter(Request $request, $idProduit, $idUser): Response
  {
     //chercher le panier de user passé en parametre
      $panier = $this->getDoctrine()->getRepository(Panier::class)->findOneBy(['iduser' => $idUser]);
      //chercher le produit passe en parametre
      $produit = $this->getDoctrine()->getRepository(Produit::class)->find($idProduit);
 
     
      // Créer une nouvelle instance de Produitcart
      $produitCart = new Produitcart();
 
      // Ajouter le produit et panier dans produitcart
      $produitCart->setIdproduit($produit);
      $produitCart->setIdpanier($panier);
 
      // Enregistrer l'entité Produitcart
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($produitCart);
      $entityManager->flush();
 
      return $this->redirectToRoute('app_produit_front');
  }
 
 
 
     //afficher la panier avec les produits choisis
     #[Route('/produitcart/afficher-panier/{idUser}', name: 'afficher_produit_panier')]
     public function afficherPanier($idUser, Request $request): Response
     {

         //user : 7atita hakeka bech najem navigi 
         $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find(2);
 
         // Récupérer le panier de l'utilisateur spécifié en parametre 
         $panier = $this->getDoctrine()->getRepository(Panier::class)->findOneBy(['iduser' => $idUser]);
         $produitCartRepository = $this->getDoctrine()->getRepository(Produitcart::class);
         $produitsDansPanier = $produitCartRepository->findBy(['idpanier' => $panier]);
 
         //selectionner produitcart selon panier specifique
         $produitCart = $this->getDoctrine()->getRepository(Produitcart::class)->findBy(['idpanier' => $panier]);

                //calcul montant facture
         $totalPrix = 0;
         // Parcourez chaque produit et ajoutez son prix au total
         foreach ($produitCart as $produit) {
             $totalPrix += $produit->getIdproduit()->getPrix(); 
         
         }
           // Créer le formulaire de saisie du code promo
           $codePromoForm = $this->createForm(CodePromoType::class);
           $codePromoForm->handleRequest($request);

    // Calculer le montant final initial (avant réduction)
    $montantFinal = $totalPrix;

    if ($codePromoForm->isSubmitted() && $codePromoForm->isValid()) {
        // Récupérer le code promo saisi par l'utilisateur
        $codePromo = $codePromoForm->getData()['code'];

        // Vérifier si le code promo existe dans la base de données
        $codePromoEntity = $this->getDoctrine()->getRepository(Codepromo::class)->findOneBy(['code' => $codePromo]);

        if ($codePromoEntity) {
            // Code promo valide, récupérer la réduction associée
            $reductionAssociee = $codePromoEntity->getReductionassocie();

            // Appliquer la réduction au montantFinal
            $montantFinal = $totalPrix * (100 - $reductionAssociee) / 100;

            // Afficher un message indiquant que le code promo est activé
            $this->addFlash('success', 'Le code promo est correct. Vous bénéficiez d\'une réduction de '.$reductionAssociee.'%. Le montant total est maintenant '.$montantFinal.' DT.');

            // Rediriger vers la même page pour afficher le nouveau montantFinal
            return $this->redirectToRoute('afficher_produit_panier', ['idUser' => $idUser]);
        } else {
            // Code promo invalide, afficher un message d'erreur
            $this->addFlash('error', 'Code promo invalide.');
        }
    }
         
 
         $quantite = [];
         $produitsUniques = [];
 
         // Parcourir chaque produit dans le produitcart et si un produit se repete plusieurs fois , on stocke dans variable quantite , et affiche ce produit une seule fois dans le tableau
         foreach ($produitCart as $produit) {
         
             if (array_key_exists($produit->getIdproduit()->getIdproduit(), $quantite)) {
                 $quantite[$produit->getIdproduit()->getIdproduit()]++;
             } else {
                 $quantite[$produit->getIdproduit()->getIdproduit()] = 1;
                 $produitsUniques[$produit->getIdproduit()->getIdproduit()] = $produit->getIdproduit();
             }
         }
        
 
         return $this->render('produitcart/panier.html.twig', [
             'produitsUniques' => $produitsUniques,
             'produitsDansPanier' => $produitsDansPanier,
             'panier' => $panier,
             'totalPrix' => $totalPrix,
             'quantite' => $quantite, // Passer le tableau de quantité à la vue
             'user'=> $user,
             'form' => $codePromoForm->createView(), // Passer la vue du formulaire à la vue Twig
             'montantFinal' => $montantFinal,

 
         ]);
     }

/*afficher la panier avec les produits choisis
#[Route('/produitcart/afficher-panier-with-code/{idUser}', name: 'afficher_produit_panier_with_code')]
public function afficherPanierCode($idUser): Response
{
    //user : 7atita hakeka bech najem navigi 
    $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find(2);

    // Récupérer le panier de l'utilisateur spécifié en parametre 
    $panier = $this->getDoctrine()->getRepository(Panier::class)->findOneBy(['iduser' => $idUser]);
    $produitCartRepository = $this->getDoctrine()->getRepository(Produitcart::class);
    $produitsDansPanier = $produitCartRepository->findBy(['idpanier' => $panier]);

    //selectionner produitcart selon panier specifique
    $produitCart = $this->getDoctrine()->getRepository(Produitcart::class)->findBy(['idpanier' => $panier]);

    //calcul montant facture
    $totalPrix = 0;
    // Parcourez chaque produit et ajoutez son prix au total
    foreach ($produitCart as $produit) {
        $totalPrix += $produit->getIdproduit()->getPrix(); 
    }
    

    $quantite = [];
    $produitsUniques = [];

    // Parcourir chaque produit dans le produitcart et si un produit se repete plusieurs fois , on stocke dans variable quantite , et affiche ce produit une seule fois dans le tableau
    foreach ($produitCart as $produit) {
    
        if (array_key_exists($produit->getIdproduit()->getIdproduit(), $quantite)) {
            $quantite[$produit->getIdproduit()->getIdproduit()]++;
        } else {
            $quantite[$produit->getIdproduit()->getIdproduit()] = 1;
            $produitsUniques[$produit->getIdproduit()->getIdproduit()] = $produit->getIdproduit();
        }
    }

    return $this->render('produitcart/panierCode.html.twig', [
        'produitsUniques' => $produitsUniques,
        'produitsDansPanier' => $produitsDansPanier,
        'panier' => $panier,
        'totalPrix' => $totalPrix,
        'quantite' => $quantite, // Passer le tableau de quantité à la vue
        'user'=> $user,

    ]);
}*/

}