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
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\DemandedonsRepository; 





class DemandeDonsController extends AbstractController
{
    
   /**
 * @Route("/demander_dons/{idUser}", name="demander_dons", methods={"GET", "POST"})
 */
public function demanderDons(Request $request, EntityManagerInterface $entityManager, $idUser): Response
{
    // Récupérer l'utilisateur spécifié par son ID depuis la base de données
    $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($idUser);

    // Si l'utilisateur n'existe pas, renvoyer une erreur
    if (!$utilisateur) {
        throw $this->createNotFoundException('Utilisateur non trouvé.');
    }

    // Si le formulaire est soumis
    if ($request->isMethod('POST')) {
        // Récupérer les données du formulaire
        $contenu = $request->request->get('contenu');
        $objectifPoints = $request->request->get('objectifPoints');
        $delai = new \DateTime($request->request->get('delai'));

        // Créer une nouvelle demande
        $demande = new Demandedons();
        $demande->setContenu($contenu);
        // Assigner l'objet Utilisateur récupéré à la demande de don
        $demande->setIdUtilisateur($utilisateur);
        $demande->setNomuser($utilisateur->getNomuser());
        $demande->setPrenomuser($utilisateur->getPrenomuser());
        $demande->setObjectifPoints($objectifPoints);
        $demande->setDelai($delai);

        // Persistez la demande dans la base de données
        $entityManager->persist($demande);
        $entityManager->flush();

        // Rediriger vers la même page pour éviter la soumission répétée du formulaire
        return $this->redirectToRoute('demander_dons', ['idUser' => $idUser]);
    }

// Calculer la différence entre la date actuelle et la date de délai pour chaque demande
$demandes = $this->getDoctrine()->getRepository(Demandedons::class)->findAll();
  // Initialiser le tableau des temps restants
  $tempsRestants = [];

  // Calculer la différence entre la date actuelle et la date de délai pour chaque demande
  foreach ($demandes as $demande) {
      $delai = $demande->getDelai();
      if ($delai) {
          $tempsRestants[$demande->getIdDemande()] = $delai->diff(new \DateTime());
      } else {
          $tempsRestants[$demande->getIdDemande()] = null;
      }
  }

    return $this->render('demande_dons/demanderdons.html.twig', [
        'utilisateur' => $utilisateur,
        'idUser' => $idUser,
        'demandes' => $demandes,
        'tempsRestants' => $tempsRestants,

    ]);
}

/**
 * @Route("/transfer_points/{idUser}", name="transfer_points", methods={"POST"})
 */
public function transferPoints(Request $request, EntityManagerInterface $entityManager, $idUser): Response
{
    // Utiliser $idUser qui est l'ID de l'utilisateur à partir de l'URL
    
    $donPoints = $request->request->get('donPoints');
    $idDemande = $request->request->get('idDemande');

    // Récupérer l'utilisateur expéditeur
    $sender = $entityManager->getRepository(Utilisateur::class)->find($idUser);
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

    // Vérifier si l'utilisateur expéditeur a suffisamment de points
    if ($sender->getNbPoints() < $donPoints) {
        return new Response('Points insuffisants pour effectuer le transfert', Response::HTTP_BAD_REQUEST);
    }

    // Mettre à jour les points de l'utilisateur expéditeur
    $sender->setNbPoints($sender->getNbPoints() - $donPoints);
    
    // Mettre à jour les points de la demande
    $newPoints = $demande->getNbPoints() + $donPoints;
    $demande->setNbPoints($newPoints);
    
    // Enregistrer les changements dans la base de données
    $entityManager->flush();

    // Rediriger vers la page demander_dons après le transfert
    return new JsonResponse(['success' => true, 'newPoints' => $newPoints]);
}



/**
 * @Route("/admin/demandedons", name="admin_demandedons")
 */
public function backDemandesDons(DemandedonsRepository $demandedonsRepository): Response
{
    // Récupérer toutes les demandes de dons depuis le repository
    $Demandedons = $demandedonsRepository->findAll();

    // Rendre la vue Twig avec la liste des demandes de dons
    return $this->render('demande_dons/backDemandeDons.html.twig', [
        'Demandedons' => $Demandedons,
    ]);
}

/**
     * @Route("/admin/demandedons/{id}/delete", name="admin_demandedons_delete", methods={"GET"})
     */
    public function deleteDemande(Request $request, DemandedonsRepository $demandedonsRepository, $id): Response


    {
        $demande = $demandedonsRepository->find($id);

        // Récupérer la demande de don à supprimer
        if (!$demande) {
            throw $this->createNotFoundException('La demande de don n\'existe pas.');
        }

        // Supprimer la demande de don de la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($demande);
        $entityManager->flush();

        // Rediriger vers la page des demandes de dons après la suppression
        return $this->redirectToRoute('admin_demandedons');
    }

}