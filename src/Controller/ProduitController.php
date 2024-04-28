<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateur;
use App\Entity\Notification;
class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/produit/like/{id}', name: 'app_produit_like', methods: ['POST'])]
    public function likeProduct($id, EntityManagerInterface $em, Request $request): Response
    {
        // Find the product by ID
        $produit = $em->getRepository(Produit::class)->find($id);

        if (!$produit) {
            // If product is not found, return an error response
            return new Response("Product not found", 404);
        }

        // Increment the product's rating (simplified example, adjust as needed)
        $currentRating = $produit->getRating() ?? 0;
        $produit->setRating($currentRating + 1);

        // Persist and flush changes to the database
        $em->persist($produit);
        $em->flush();

        return new Response("Product liked", 200);
    }

    // Route to handle "dislike" action for a product
    #[Route('/produit/dislike/{id}', name: 'app_produit_dislike', methods: ['POST'])]
    public function dislikeProduct($id, EntityManagerInterface $em, Request $request): Response
    {
        // Find the product by ID
        $produit = $em->getRepository(Produit::class)->find($id);

        if (!$produit) {
            // If product is not found, return an error response
            return new Response("Product not found", 404);
        }

        // Decrement the product's rating (simplified example, adjust as needed)
        $currentRating = $produit->getRating() ?? 0;
        if ($currentRating > 0) {
            $produit->setRating($currentRating - 1);
        }

        // Persist and flush changes to the database
        $em->persist($produit);
        $em->flush();

        return new Response("Product disliked", 200);
    }

 //afficher produits dans front + connecté user 1
#[Route('/produit/frontProduit', name: 'app_produit_front')]
public function afficherFront(Request $request): Response
{
    
$notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
$user = $this->getDoctrine()->getRepository(Utilisateur::class)->find(2);
$produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
$categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

return $this->render('produit/frontProduit.html.twig', [
    'produits' => $produits,
     'user'=> $user,
     'categories' => $categories,
     'notifications' => $notifications,


]);
}
//filtrer products selon categorie choisi dans la partie front
#[Route('/produit/frontProduit/{id}', name: 'app_categorie_front')]
public function produitsParCategorie(Request $request,$id): Response
{
    $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find(1);

    $produits = $this->getDoctrine()->getRepository(Produit::class)->findBy(['categorie' => ['idcategoire' => $id]]);
    $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

    return $this->render('produit/frontProduit.html.twig', [
        'produits' => $produits,
        'user'=> $user,
         'categories' => $categories,


    ]);
}

}
