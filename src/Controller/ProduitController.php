<?php

namespace App\Controller;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateur;
use Knp\Component\Pager\PaginatorInterface;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find(2);
        
        return $this->render('base.html.twig', [
            'controller_name' => 'ProduitController',
            'user'=> $user,

        ]);
    }



    //ajouter et afficher
    #[Route('/produit/ajouter', name: 'app_produit_ajouter')]
    public function ajouter(Request $request): Response
{
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit,);
    $form->handleRequest($request);
    $entityManager = $this->getDoctrine()->getManager();

    if ($form->isSubmitted() && $form->isValid()) {

        // Récupérer le nom du produit à partir du formulaire
        $nomProduit = $form->get('nomproduit')->getData();
        $prixProduit = $form->get('prix')->getData();
        // Vérifier si un produit avec le même nom et prix existe déjà dans la base de données
        $existingProduit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy([
            'nomproduit' => $nomProduit,
            'prix' => $prixProduit
        ]);
        if ($existingProduit) {
            // Si le produit existe déjà, augmenter sa quantité
            $existingProduit->setQuantite($existingProduit->getQuantite() + 1);
        }
        else {
        /** @var UploadedFile $image */
        $image = $form->get('imageproduit')->getData();

        // Vérifiez si une image a été téléchargée
        if ($image) {
            // Générez un nom de fichier unique
            $nomFichier = md5(uniqid()).'.'.$image->guessExtension();

            // Déplacez le fichier vers le répertoire public/images
            $image->move(
                $this->getParameter('images_directory'), // Le chemin vers votre répertoire Images dans le dossier public
                $nomFichier
            );

            // Définir le nom du fichier de l'image de catégorie dans l'entité
            $produit->setImageproduit($nomFichier);
        }
    
        // Enregistrez la catégorie dans la base de données
        $entityManager->persist($produit);
    }
        $entityManager->flush();

        // Redirigez l'utilisateur après l'ajout réussi
        return $this->redirectToRoute('app_produit_ajouter');
    
}
    //affichage
    $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
 
    
    $categories = $this->getDoctrine()
    ->getRepository(Categorie::class)
    ->createQueryBuilder('c')
    ->select('c, COUNT(p.categorie) as count')
    ->leftJoin(Produit::class, 'p', 'WITH', 'p.categorie = c.idcategorie')
    ->groupBy('c.idcategorie')
    ->orderBy('count', 'DESC')
    ->getQuery()
    ->getResult();

    return $this->render('produit/ajouterProduit.html.twig', [
        'form' => $form->createView(),
        'produits' => $produits,
        'categories' => $categories,

    ]);
}



//chercher un produit
#[Route('/produit/search', name: 'app_produit_search')]

public function searchAjax(Request $request): Response
{
    
    // Rendre la vue partielle pour les résultats de la recherche
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit,);
    $form->handleRequest($request);
    $entityManager = $this->getDoctrine()->getManager();

    if ($form->isSubmitted() && $form->isValid()) {

        // Récupérer le nom du produit à partir du formulaire
        $nomProduit = $form->get('nomproduit')->getData();
        $prixProduit = $form->get('prix')->getData();
        // Vérifier si un produit avec le même nom et prix existe déjà dans la base de données
        $existingProduit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy([
            'nomproduit' => $nomProduit,
            'prix' => $prixProduit
        ]);
        if ($existingProduit) {
            // Si le produit existe déjà, augmenter sa quantité
            $existingProduit->setQuantite($existingProduit->getQuantite() + 1);
        }
        else {
        /** @var UploadedFile $image */
        $image = $form->get('imageproduit')->getData();

        // Vérifiez si une image a été téléchargée
        if ($image) {
            // Générez un nom de fichier unique
            $nomFichier = md5(uniqid()).'.'.$image->guessExtension();

            // Déplacez le fichier vers le répertoire public/images
            $image->move(
                $this->getParameter('images_directory'), // Le chemin vers votre répertoire Images dans le dossier public
                $nomFichier
            );

            // Définir le nom du fichier de l'image de catégorie dans l'entité
            $produit->setImageproduit($nomFichier);
        }
    
        // Enregistrez la catégorie dans la base de données
        $entityManager->persist($produit);
    }
        $entityManager->flush();

        // Redirigez l'utilisateur après l'ajout réussi
        return $this->redirectToRoute('app_produit_ajouter');
    
}

$searchTerm = $request->query->get('search');

// Effectuer la recherche en fonction du contenu du champ de recherche
$produits = $this->getDoctrine()->getRepository(Produit::class)->searchByKeywordOrPriceOrQuantity($searchTerm);
    
    $categories = $this->getDoctrine()
    ->getRepository(Categorie::class)
    ->createQueryBuilder('c')
    ->select('c, COUNT(p.categorie) as count')
    ->leftJoin(Produit::class, 'p', 'WITH', 'p.categorie = c.idcategorie')
    ->groupBy('c.idcategorie')
    ->orderBy('count', 'DESC')
    ->getQuery()
    ->getResult();

    return $this->render('produit/ajouterProduit.html.twig', [
        'form' => $form->createView(),
        'produits' => $produits,
        'categories' => $categories,
        'searchTerm' => $searchTerm,

    ]);
}


/*
//chercher un produit selon categorie donnée dans search
#[Route('/produit/searchcateg', name: 'app_produit_search')]
public function searchcateg(Request $request, ProduitRepository $produitRepository): Response
{
    //ajout
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit,);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $image = $form->get('imageproduit')->getData();

        // Vérifiez si une image a été téléchargée
        if ($image) {
            // Générez un nom de fichier unique
            $nomFichier = md5(uniqid()).'.'.$image->guessExtension();

            // Déplacez le fichier vers le répertoire public/images
            $image->move(
                $this->getParameter('images_directory'), // Le chemin vers votre répertoire Images dans le dossier public
                $nomFichier
            );

            // Définir le nom du fichier de l'image de catégorie dans l'entité
            $produit->setImageproduit($nomFichier);
        }

        // Enregistrez la catégorie dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produit);
        $entityManager->flush();

        // Redirigez l'utilisateur après l'ajout réussi
        return $this->redirectToRoute('app_produit_ajouter');
    }

    // Récupérer le mot-clé de la requête GET
    $keyword = $request->query->get('keyword');

    // Recherche de produits correspondant au mot-clé
    $produits = $produitRepository->findByCategoryName($keyword); 
    $categories = $this->getDoctrine()
    ->getRepository(Categorie::class)
    ->createQueryBuilder('c')
    ->select('c, COUNT(p.categorie) as count')
    ->leftJoin(Produit::class, 'p', 'WITH', 'p.categorie = c.idcategorie')
    ->groupBy('c.idcategorie')
    ->orderBy('count', 'DESC')
    ->getQuery()
    ->getResult();
    return $this->render('produit/ajouterProduit.html.twig', [
        'form' => $form->createView(),
        'produits' => $produits,
        'keyword' => $keyword,
        'categories' => $categories,

    ]);
}
*/



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



//supprimer un produit
#[Route('/produit/supprimer/{id}', name: 'app_produit_supprimer')]
public function supprimer($id, ProduitRepository $repository): Response
{
    $list = $repository->find($id);
    $em = $this->getDoctrine()->getManager();
    $em->remove($list);
    $em->flush();
    return $this->redirectToRoute('app_produit_ajouter');
}




//modifier un produit
#[Route('/produit/edit/{id}', name: 'app_produit_edit')]
public function edit(ProduitRepository $repository, $id, Request $request)
{
    $produit = $repository->find($id);
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted()&& $form->isValid()) {
        // Si le formulaire est soumis et valide, procédez à la sauvegarde des modifications
        $image = $form->get('imageproduit')->getData();

        // Vérifiez si une image a été téléchargée
        if ($image) {
            // Générez un nom de fichier unique
            $nomFichier = md5(uniqid()).'.'.$image->guessExtension();

            // Déplacez le fichier vers le répertoire public/images
            $image->move(
                $this->getParameter('images_directory'), // Le chemin vers votre répertoire Images dans le dossier public
                $nomFichier
            );

            // Définir le nom du fichier de l'image de catégorie dans l'entité
            $produit->setImageproduit($nomFichier);
        }
        $em = $this->getDoctrine()->getManager();
            $em->flush();
        return $this->redirectToRoute('app_produit_ajouter');
    }

    return $this->renderForm("produit/editProduit.html.twig", ["form" => $form]);

}



//trie prix asc
#[Route('/produit/prixAsc', name: 'app_prix_asc')]
    public function triePrixAsc(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit,);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('imageproduit')->getData();
    
            // Vérifiez si une image a été téléchargée
            if ($image) {
                // Générez un nom de fichier unique
                $nomFichier = md5(uniqid()).'.'.$image->guessExtension();
    
                // Déplacez le fichier vers le répertoire public/images
                $image->move(
                    $this->getParameter('images_directory'), // Le chemin vers votre répertoire Images dans le dossier public
                    $nomFichier
                );
    
                // Définir le nom du fichier de l'image de catégorie dans l'entité
                $produit->setImageproduit($nomFichier);
            }
    
            // Enregistrez la catégorie dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
    
            // Redirigez l'utilisateur après l'ajout réussi
            return $this->redirectToRoute('app_produit_ajouter');
        }
        $categories = $this->getDoctrine()
        ->getRepository(Categorie::class)
        ->createQueryBuilder('c')
        ->select('c, COUNT(p.categorie) as count')
        ->leftJoin(Produit::class, 'p', 'WITH', 'p.categorie = c.idcategorie')
        ->groupBy('c.idcategorie')
        ->orderBy('count', 'DESC')
        ->getQuery()
        ->getResult();
        
          $produits = $this->getDoctrine()->getRepository(Produit::class)->findBy([], ['prix' => 'ASC']);
        return $this->render('produit/ajouterProduit.html.twig', [
            'form' => $form->createView(),
        'produits' => $produits,
        'categories' => $categories,
                ]);
    }


//trie prix desc
#[Route('/produit/prixDesc', name: 'app_prix_desc')]
public function triePrixDesc(Request $request): Response
{
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit,);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $image */
        $image = $form->get('imageproduit')->getData();

        // Vérifiez si une image a été téléchargée
        if ($image) {
            // Générez un nom de fichier unique
            $nomFichier = md5(uniqid()).'.'.$image->guessExtension();

            // Déplacez le fichier vers le répertoire public/images
            $image->move(
                $this->getParameter('images_directory'), // Le chemin vers votre répertoire Images dans le dossier public
                $nomFichier
            );

            // Définir le nom du fichier de l'image de catégorie dans l'entité
            $produit->setImageproduit($nomFichier);
        }

        // Enregistrez la catégorie dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produit);
        $entityManager->flush();

        // Redirigez l'utilisateur après l'ajout réussi
        return $this->redirectToRoute('app_produit_ajouter');
    }
    $categories = $this->getDoctrine()
    ->getRepository(Categorie::class)
    ->createQueryBuilder('c')
    ->select('c, COUNT(p.categorie) as count')
    ->leftJoin(Produit::class, 'p', 'WITH', 'p.categorie = c.idcategorie')
    ->groupBy('c.idcategorie')
    ->orderBy('count', 'DESC')
    ->getQuery()
    ->getResult();
    
      $produits = $this->getDoctrine()->getRepository(Produit::class)->findBy([], ['prix' => 'DESC']);
    return $this->render('produit/ajouterProduit.html.twig', [
        'form' => $form->createView(),
    'produits' => $produits,
    'categories' => $categories,
            ]);
}

//trie nomProduit desc
#[Route('/produit/nomAsc', name: 'app_nomproduit_asc')]
public function trieNomAsc(Request $request): Response
{
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit,);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $image */
        $image = $form->get('imageproduit')->getData();

        // Vérifiez si une image a été téléchargée
        if ($image) {
            // Générez un nom de fichier unique
            $nomFichier = md5(uniqid()).'.'.$image->guessExtension();

            // Déplacez le fichier vers le répertoire public/images
            $image->move(
                $this->getParameter('images_directory'), // Le chemin vers votre répertoire Images dans le dossier public
                $nomFichier
            );

            // Définir le nom du fichier de l'image de catégorie dans l'entité
            $produit->setImageproduit($nomFichier);
        }

        // Enregistrez la catégorie dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produit);
        $entityManager->flush();

        // Redirigez l'utilisateur après l'ajout réussi
        return $this->redirectToRoute('app_produit_ajouter');
    }
    $categories = $this->getDoctrine()
    ->getRepository(Categorie::class)
    ->createQueryBuilder('c')
    ->select('c, COUNT(p.categorie) as count')
    ->leftJoin(Produit::class, 'p', 'WITH', 'p.categorie = c.idcategorie')
    ->groupBy('c.idcategorie')
    ->orderBy('count', 'DESC')
    ->getQuery()
    ->getResult();
    
      $produits = $this->getDoctrine()->getRepository(Produit::class)->findBy([], ['nomproduit' => 'ASC']);
    return $this->render('produit/ajouterProduit.html.twig', [
        'form' => $form->createView(),
    'produits' => $produits,
    'categories' => $categories,
            ]);
}


//trie nomProduit desc
#[Route('/produit/nomDesc', name: 'app_nomproduit_desc')]
public function trieNomDesc(Request $request): Response
{
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit,);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $image */
        $image = $form->get('imageproduit')->getData();

        // Vérifiez si une image a été téléchargée
        if ($image) {
            // Générez un nom de fichier unique
            $nomFichier = md5(uniqid()).'.'.$image->guessExtension();

            // Déplacez le fichier vers le répertoire public/images
            $image->move(
                $this->getParameter('images_directory'), // Le chemin vers votre répertoire Images dans le dossier public
                $nomFichier
            );

            // Définir le nom du fichier de l'image de catégorie dans l'entité
            $produit->setImageproduit($nomFichier);
        }

        // Enregistrez la catégorie dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produit);
        $entityManager->flush();

        // Redirigez l'utilisateur après l'ajout réussi
        return $this->redirectToRoute('app_produit_ajouter');
    }
    $categories = $this->getDoctrine()
    ->getRepository(Categorie::class)
    ->createQueryBuilder('c')
    ->select('c, COUNT(p.categorie) as count')
    ->leftJoin(Produit::class, 'p', 'WITH', 'p.categorie = c.idcategorie')
    ->groupBy('c.idcategorie')
    ->orderBy('count', 'DESC')
    ->getQuery()
    ->getResult();
    
      $produits = $this->getDoctrine()->getRepository(Produit::class)->findBy([], ['nomproduit' => 'DESC']);
    return $this->render('produit/ajouterProduit.html.twig', [
        'form' => $form->createView(),
    'produits' => $produits,
    'categories' => $categories,
            ]);
}

}
