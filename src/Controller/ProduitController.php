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
class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

 //afficher produits dans front + connecté user 1
#[Route('/produit/frontProduit', name: 'app_produit_front')]
public function afficherFront(Request $request): Response
{
$user = $this->getDoctrine()->getRepository(Utilisateur::class)->find(2);
$produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
$categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

return $this->render('produit/frontProduit.html.twig', [
    'produits' => $produits,
     'user'=> $user,
     'categories' => $categories,


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
#[Route('/produit/frontProduit/rate_produit/{id}', name: 'rate_produit')]
public function rateProduit(Request $request, $idProduit): Response {
    $rating = $request->request->get('rating');
    $produit = $this->getDoctrine()->getRepository(Produit::class)->find($idProduit);

    if ($produit) {
        $total_rating = ($produit->getAverageRating() * $produit->getReviewCount()) + $rating;
        $new_review_count = $produit->getReviewCount() + 1;
        $average_rating = $total_rating / $new_review_count;

        $produit->setAverageRating($average_rating);
        $produit->setReviewCount($new_review_count);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produit);
        $entityManager->flush();

        return new Response('Rating submitted');
    }

    return new Response('Produit not found', 404);
}
}
