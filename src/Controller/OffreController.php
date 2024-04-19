<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Offre;
use App\Form\OffreType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\OffreRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\UX\Modal\Modal;
use Knp\Component\Pager\PaginatorInterface;

class OffreController extends AbstractController
{
    #[Route('/ajouter-offre', name: 'ajouter_offre')]
    public function ajouterOffre(Request $request): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les produits sélectionnés
            $produits = $form->get('produits')->getData();

            // Associer chaque produit à l'offre
            foreach ($produits as $produit) {
                $produit->setOffre($offre);
            }

            /** @var UploadedFile $image */
            $image = $form->get('imageoffre')->getData();

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
                $offre->setImageoffre($nomFichier);
            }

            // Enregistrez la catégorie dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();

            $this->addFlash('success', 'Offre ajoutée avec succès.');

            // Redirigez l'utilisateur après l'ajout réussi
            return $this->redirectToRoute('afficher_offres');
        }

        // Récupérer tous les produits depuis la base de données
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();

        // Affichage du formulaire
        return $this->render('offre/ajouter.html.twig', [
            'form' => $form->createView(),
            'produits' => $produits, // Passer les produits au modèle Twig
        ]);
    }
#[Route('/afficher-offres', name: 'afficher_offres')]
    public function afficherOffres(Request $request,PaginatorInterface $paginator): Response
    {
        // Récupérer toutes les offres depuis la base de données
        $offres = $this->getDoctrine()->getRepository(Offre::class)->findAll();

        $offres = $paginator->paginate(
        $offres, 
        $request->query->getInt('page', 1), // Numéro de page par défaut
        2 // Nombre d'éléments par page
        );

        return $this->render('offre/afficher.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/modifier-offre/{id}', name: 'modifier_offre')]
public function modifier(Request $request, int $id): Response {
    $entityManager = $this->getDoctrine()->getManager();
    $offre = $entityManager->getRepository(Offre::class)->find($id);

    if (!$offre) {
        throw $this->createNotFoundException("No offer found for id ".$id);
    }
   
    // Sauvegarder le nom de l'ancienne image
    $ancienneImage = $offre->getImageoffre();

    $form = $this->createForm(OffreType::class, $offre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

     // Récupérer les produits sélectionnés
     $produitsSelectionnes = $form->get('produits')->getData();

     // Récupérer tous les produits actuellement associés à l'offre
     $produitsAssocies = $offre->getProduits();

     // Dissocier chaque produit qui n'est pas sélectionné de l'offre
     foreach ($produitsAssocies as $produit) {
        // Vérifiez si le produit actuel n'est pas sélectionné dans le formulaire
        if (!$produitsSelectionnes->contains($produit)) {
            // Dissociez le produit de l'offre
            $produit->setOffre(null);
            // Persistez le produit pour mettre à jour la base de données
            $entityManager->persist($produit);
        }
    }
    

     // Mettre à jour les nouvelles associations produit-offre avec l'offre modifiée
     foreach ($produitsSelectionnes as $produit) {
         $offre->addProduit($produit);
         $produit->setOffre($offre);
         $entityManager->persist($produit); // Persistez le produit pour mettre à jour la base de données
     }

        /** @var UploadedFile|null $image */
        $image = $form->get('imageoffre')->getData();

        if ($image) {
            $nomFichier = md5(uniqid()).'.'.$image->guessExtension();
            try {
                $image->move($this->getParameter('images_directory'), $nomFichier);
                $offre->setImageoffre($nomFichier);
            } catch (FileException $e) {
                // Gérer l'exception si quelque chose se passe mal pendant le téléchargement du fichier
            }
        } else {
            // Si aucune nouvelle image n'est téléchargée, conserver l'ancienne image
            $offre->setImageoffre($ancienneImage);
        }

        $entityManager->flush();
        $this->addFlash('success', "L'offre a été modifiée avec succès.");

        return $this->redirectToRoute('afficher_offres');
    }

    return $this->render('offre/modifier.html.twig', [
        'offre' => $offre,
        'form' => $form->createView(),
    ]);
}


#[Route('/supprimer-offre/{id}', name: 'supprimer_offre')]
    public function supprimerOffre(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        // Récupérer l'offre à supprimer depuis la base de données
        $offre = $entityManager->getRepository(Offre::class)->find($id);
    
        // Vérifier si l'offre existe
        if (!$offre) {
            throw $this->createNotFoundException('Aucune offre trouvée pour cet identifiant.');
        }
    
        // Récupérer tous les produits associés à cette offre
        $produits = $offre->getProduits();
    
        // Dissocier chaque produit de l'offre
        foreach ($produits as $produit) {
            $produit->setOffre(null); // Dissocier le produit de l'offre
        }
    
        // Supprimer l'offre de la base de données
        $entityManager->remove($offre);
        $entityManager->flush();
    
        // Ajouter un message flash pour indiquer la suppression réussie
        $this->addFlash('success', 'Offre supprimée avec succès.');
    
        // Rediriger vers la page d'affichage des offres
        return $this->redirectToRoute('afficher_offres');
    }


    #[Route('/consulter-offre/{id}', name: 'consulter_offre')]
    public function consulterOffre(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $offre = $entityManager->getRepository(Offre::class)->find($id);

        // Vérifier si l'offre existe
        if (!$offre) {
            throw $this->createNotFoundException('Aucune offre trouvée pour cet identifiant.');
        }
        return $this->render('offre/consulter.html.twig', [
            'offre' => $offre,
        ]);
    }
    #[Route('/afficher-calendrier', name: 'afficher_calendrier')]
public function afficherCalendrier(): JsonResponse
{
    $entityManager = $this->getDoctrine()->getManager();
    $offres = $entityManager->getRepository(Offre::class)->findAll();

    $events = [];

    foreach ($offres as $offre) {
        $nomOffre = $offre->getNomoffre();
        $dateDebut = $offre->getDatedebut();
        $dateFin = $offre->getDatefin();

        // Créez un intervalle d'une journée
        $interval = new DateInterval('P1D');

        // Déterminez le nombre de jours entre la date de début et la date de fin
        $nombreJours = $dateFin->diff($dateDebut)->days;

        // Créez un objet DateTime pour la date de début
        $currentDate = clone $dateDebut;

        // Parcourez chaque jour entre la date de début et la date de fin
        for ($i = 0; $i <= $nombreJours; $i++) {
            $events[] = [
                'title' => $nomOffre,
                'start' => $currentDate->format('Y-m-d')
            ];
            $currentDate->add($interval); // Ajoutez un jour à la date actuelle
        }
    }

    return new JsonResponse($events);
}

#[Route('/afficher-offres', name: 'afficher_offres')]
public function afficherOffresfiltre(Request $request, OffreRepository $offreRepository): Response
{
    $searchQuery = $request->query->get('search_query');

    // Analysez la recherche de l'utilisateur
    $nomOffre = null;
    $reduction = null;

    // Si une recherche est effectuée
    if ($searchQuery) {
        // Vérifiez si la recherche correspond à une réduction (uniquement des chiffres)
        if (ctype_digit($searchQuery)) {
            $reduction = $searchQuery;
        } else {
            $nomOffre = $searchQuery;
        }
    }

    // Utilisez la méthode findByCriteria du repository pour rechercher les offres
    $offres = $offreRepository->findByCriteria($nomOffre, $reduction);

    return $this->render('offre/afficher.html.twig', [
        'offres' => $offres,
    ]);
}




#[Route('/afficher-offres', name: 'afficher_offres')]
public function findByCriteriaTriDate(Request $request, OffreRepository $offreRepository, $sort_by = 'datedebut'): Response
{
    $sortOrder = $request->query->get('sort_order', 'asc');

    // Vérifiez si l'ordre de tri est valide
    $validSortOrders = ['asc', 'desc'];
    if (!in_array($sortOrder, $validSortOrders)) {
        throw new \InvalidArgumentException('Invalid sort order.');
    }

    // Utilisez la méthode findByCriteria du repository pour rechercher les offres
    $offres = $offreRepository->findByCriteriaTriDate([], $sort_by, $sortOrder);

    return $this->render('offre/afficher.html.twig', [
        'offres' => $offres,
        'sort_by' => $sort_by,
        'sort_order' => $sortOrder,
    ]);
}

#[Route('/afficher-offres', name: 'afficher_offres')]
public function findByCriteriaTriReduction(Request $request, OffreRepository $offreRepository, $sort_by = 'reduction'): Response
{
    $sortOrder = $request->query->get('sort_order', 'asc');

    // Vérifiez si l'ordre de tri est valide
    $validSortOrders = ['asc', 'desc'];
    if (!in_array($sortOrder, $validSortOrders)) {
        throw new \InvalidArgumentException('Invalid sort order.');
    }

    // Utilisez la méthode findByCriteria du repository pour rechercher les offres
    $offres = $offreRepository->findByCriteriaTriReduction([], $sort_by, $sortOrder);

    return $this->render('offre/afficher.html.twig', [
        'offres' => $offres,
        'sort_by' => $sort_by,
        'sort_order' => $sortOrder,
    ]);
}
}