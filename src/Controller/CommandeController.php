<?php

namespace App\Controller;
use App\Entity\Panier;
use App\Entity\Commande;
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

    #[Route('/commande/commande-back', name: 'app_commande_back')]
    public function afficherBack(Request $request): Response
{
    
    $commande = $this->getDoctrine()->getRepository(Commande::class)->findAll();
    
    return $this->render('commande/backCommande.html.twig', [
        'commande' => $commande,

    ]);
}
    #[Route('/commande/{idp}', name: 'app_commande_add')]
    public function ajouter(Request $request,$idp): Response
    {
      
        $panier = $this->getDoctrine()->getRepository(Panier::class)->find($idp);
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
