<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Demandedons;
use App\Entity\Utilisateur; // Importez l'entité Utilisateur
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;


class DemandeDonsController extends AbstractController
{
    /**
     * @Route("/demander_dons", name="demander_dons", methods={"GET", "POST"})
     */
    public function demanderDons(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les demandes de dons
        $demandes = $this->getDoctrine()->getRepository(Demandedons::class)->findAll();

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            // Récupérer l'utilisateur avec l'ID 1
            $utilisateur = $entityManager->getRepository(Utilisateur::class)->find(1);

            // Récupérer le contenu de la demande depuis la requête POST
            $contenu = $request->request->get('contenu');

            // Créer une nouvelle demande
            $demande = new Demandedons();
            $demande->setContenu($contenu);
            
            // Associer l'utilisateur à la demande
            if ($utilisateur) {
                $demande->setIdUtilisateur($utilisateur);
                $demande->setNomuser($utilisateur->getNomuser()); 
                $demande->setPrenomuser($utilisateur->getPrenomuser()); 
            }

            // Persistez la demande dans la base de données
            $entityManager->persist($demande);
            $entityManager->flush();

            // Rediriger vers la même page pour éviter la soumission répétée du formulaire
            return $this->redirectToRoute('demander_dons');
        }

        // Afficher la page avec les demandes de dons
        return $this->render('demande_dons/demanderdons.html.twig', [
            'demandes' => $demandes,
        ]);
    }

/**
 * @Route("/transfer_points", name="transfer_points", methods={"POST"})
 */
public function transferPoints(Request $request, EntityManagerInterface $entityManager): Response
{
    $userId = 2; // ID de l'utilisateur qui transfère les points
    $donPoints = $request->request->get('donPoints');
    $idDemande = $request->request->get('idDemande');

    // Récupérer l'utilisateur expéditeur
    $sender = $entityManager->getRepository(Utilisateur::class)->find($userId);
    if (!$sender) {
        return new Response('Utilisateur expéditeur non trouvé', Response::HTTP_NOT_FOUND);
    }

    // Récupérer la demande de don associée à l'ID
    $demande = $entityManager->getRepository(Demandedons::class)->find($idDemande);
    if (!$demande) {
        return new Response('Demande de don non trouvée', Response::HTTP_NOT_FOUND);
    }

    // Vérifier que les données sont valides
    if (!is_numeric($donPoints) || $donPoints <= 0) {
        return new Response('Nombre de points invalide', Response::HTTP_BAD_REQUEST);
    }

    // Mettre à jour les points de l'utilisateur expéditeur
    $sender->setNbPoints($sender->getNbPoints() - $donPoints);
    
    // Mettre à jour les points de la demande
    $newPoints = $demande->getNbPoints() + $donPoints;
    $demande->setNbPoints($newPoints);
    
    // Enregistrer les changements dans la base de données
    $entityManager->flush();

    // Retourner une réponse avec un message de succès
    return new Response('Points transférés avec succès', Response::HTTP_OK);
}
}