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


}
