<?php

namespace App\Controller;

use App\Entity\Dons;
use App\Entity\Utilisateur;
use App\Form\DonsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DonsRepository;


class DonsController extends AbstractController
{
    /**
     * @Route("/dons", name="dons_page")
     */
    public function index(Request $request, DonsRepository $donsRepository): Response
    {
        // Créer un nouvel objet Dons
        $don = new Dons();
    
        // Créer un formulaire en utilisant le formulaire DonsType
        $form = $this->createForm(DonsType::class, $don);
    
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
    
      
    // Récupérer les dons de l'utilisateur ayant l'ID 1 depuis la base de données
    $userId = 1; // ID de l'utilisateur
    $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($userId);
        
        // Vérifier si l'utilisateur existe
        if (!$utilisateur) {
            throw $this->createNotFoundException('Aucun utilisateur trouvé avec l\'ID 1.');
        }

    $dons = $donsRepository->getDonsByUserId($userId);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur d'ID 1 depuis la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $utilisateur = $entityManager->getRepository(Utilisateur::class)->find(1);

            // Si aucun utilisateur n'est trouvé, rediriger vers une erreur ou gérer d'une autre manière
            if (!$utilisateur) {
                throw $this->createNotFoundException('Aucun utilisateur trouvé avec l\'ID 1.');
            }

            // Définir l'utilisateur pour le don
            $don->setIdUser($utilisateur);

            // Définir l'état du don comme "en attente"
            $don->setEtatstatutdons('en attente');

            // Récupérer le nombre de points du formulaire
            $nbPointsDon = $don->getNbpoints();

            // Soustraire les points du don aux points de l'utilisateur
            $utilisateur->setNbPoints($utilisateur->getNbPoints() - $nbPointsDon);

            // Persister à la fois l'objet Dons et l'objet Utilisateur
            $entityManager->persist($don);
            $entityManager->persist($utilisateur);

            // Exécuter les requêtes SQL
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page des dons
            return $this->redirectToRoute('dons_page');
        }

      // Rendre la vue Twig avec le formulaire et les dons
      return $this->render('dons/Dons.html.twig', [
        'form' => $form->createView(),
        'dons' => $dons, // Passer les dons au template Twig
        'nbPointsUtilisateur' => $utilisateur->getNbPoints(),

    ]);}

      /**
     * @Route("/user/{userId}/dons", name="user_dons")
     */
    public function getDonsByUserId(DonsRepository $donsRepository, int $userId): Response
    {
        $dons = $donsRepository->getDonsByUserId($userId);

        return $this->render('dons/Dons.html.twig', [
            'dons' => $dons,
        ]);
    }

 /**
     * @Route("/dons/{id}/delete", name="don_delete", methods={"POST"})
     */
    public function delete(Request $request, $id): Response
    {
        // Récupérer le don à supprimer depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $don = $entityManager->getRepository(Dons::class)->find($id);

        // Vérifier si le don existe
        if (!$don) {
            throw $this->createNotFoundException('Le don avec l\'ID '.$id.' n\'existe pas.');
        }

        // Supprimer le don
        $entityManager->remove($don);
        $entityManager->flush();

        // Rediriger vers la page des dons ou une autre page après la suppression
        return $this->redirectToRoute('dons_page');
    }
    /**
     * @Route("/edit/{id}", name="edit_don")
     */
    public function editDon(Request $request, int $id): Response
    {
        // Récupérer le don correspondant à l'identifiant
        $don = $this->getDoctrine()->getRepository(Dons::class)->find($id);

        // Vérifier si le don existe
        if (!$don) {
            throw $this->createNotFoundException('Le don avec l\'identifiant '.$id.' n\'existe pas.');
        }

        // Créer le formulaire avec le type de formulaire DonsType
        $form = $this->createForm(DonsType::class, $don);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($don);
            $entityManager->flush();

            // Rediriger l'utilisateur vers une autre page après l'édition
            return $this->redirectToRoute('dons_page');
        }

        // Afficher le formulaire dans le template
        return $this->render('dons/edit_modal.html.twig', [
            'form' => $form->createView(),
            'don' => $don, // Passer la variable "don" au template
        ]);
    }

     /**
     * @Route("/demander-dons", name="demander_dons")
     */
    public function demanderDons(): Response
    {
        return $this->render('dons/demandedons.html.twig');
    }
}
