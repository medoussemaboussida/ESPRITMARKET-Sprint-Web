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

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/produit/ajouter', name: 'app_produit_ajouter')]
    public function ajouter(Request $request): Response
{
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit);
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
    //affichage
    $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
 
    return $this->render('produit/ajouterProduit.html.twig', [
        'form' => $form->createView(),
        'produits' => $produits,

    ]);
}
#[Route('/produit/frontProduit', name: 'app_produit_front')]
    public function afficherFront(Request $request): Response
{
    $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find(1);
    $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
    return $this->render('produit/frontProduit.html.twig', [
        'produits' => $produits,
         'user'=> $user,

    ]);
}
#[Route('/produit/supprimer/{id}', name: 'app_produit_supprimer')]
public function supprimer($id, ProduitRepository $repository): Response
{
    $list = $repository->find($id);
    $em = $this->getDoctrine()->getManager();
    $em->remove($list);
    $em->flush();
    return $this->redirectToRoute('app_produit_ajouter');
}


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


}
