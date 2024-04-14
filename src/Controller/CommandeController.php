<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Commande;
use App\Entity\Produitcart;
use App\Repository\PanierRepository;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use DateTimeImmutable;
class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
       //passer une commande dans partie front
       #[Route('/commande/{idp}', name: 'app_commande_add')]
       public function ajouter(Request $request,$idp): Response
       {
         
           $panier = $this->getDoctrine()->getRepository(Panier::class)->find($idp);
           $produitCart = $this->getDoctrine()->getRepository(Produitcart::class)->findBy(['idpanier' => $idp]);
           if (empty($produitCart)) {
               // Si la table produitcart est vide, rediriger l'utilisateur ou afficher un message d'erreur
               $this->addFlash('error', 'Votre panier est vide.');
               return $this->redirectToRoute('app_produit_front');
           }
           else {
           //calcul montant facture pour l'envoyer sur sms
           $totalPrix = 0;
           foreach ($produitCart as $produit) {
               $totalPrix += $produit->getIdproduit()->getPrix(); 
           }
           
           $dateCommande = new DateTimeImmutable();
           // Créer une nouvelle instance de Produitcart
           $commande = new Commande();
   
           // Ajouter le produit au panier
           $commande->setIdpanier($panier);
           $commande->setDatecommande($dateCommande);
           if (!$commande->getIdpanier()) {
               throw new \ErrorException('idpanier field cannot be null.');
           }
           // Enregistrer l'entité Produitcart
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($commande);
           $entityManager->flush();
           $this->addFlash('success', 'Votre commande a bien été passée.');
   
           return $this->redirectToRoute('app_produit_front');
       }
       }
   
   
   
   
       //annuler commande depuis front
       #[Route('/commande/delete/{idp}', name: 'app_commande_delete')]
       public function delete(Request $request,$idp): Response
       {
           // Récupérer toutes les commandes liées au panier avec l'ID donné
       $commandes = $this->getDoctrine()->getRepository(Commande::class)->findBy(['idpanier' => $idp]);
       $produitCart = $this->getDoctrine()->getRepository(Produitcart::class)->findBy(['idpanier' => $idp]);
   
       if (empty($produitCart)) {
           // Si la table produitcart est vide, rediriger l'utilisateur ou afficher un message d'erreur
           $this->addFlash('error', 'Votre panier est vide.');
           return $this->redirectToRoute('app_produit_front');
       }
       else {
       $dateActuelle = new DateTimeImmutable();
   
       // Supprimer chaque commande individuellement
       $entityManager = $this->getDoctrine()->getManager();
       foreach ($commandes as $commande) {
           $commandeDateFormatted = $commande->getDatecommande()->format('Y-m-d');
           // Convertir la date actuelle en une chaîne de caractères au format 'Y-m-d'
           $dateActuelleFormatted = $dateActuelle->format('Y-m-d');
   
           if ($commandeDateFormatted === $dateActuelleFormatted)
           {
           $entityManager->remove($commande);
           }
       }
   
       $entityManager->flush();
   
       // Rediriger l'utilisateur vers une page appropriée après la suppression des commandes
       $this->addFlash('success', 'Votre Commande est annulée avec succès.');
       return $this->redirectToRoute('app_produit_front');
   }
       }
       
   
}
