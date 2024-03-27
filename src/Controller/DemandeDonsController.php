<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Demandedons;
use App\Entity\Utilisateur; // Importez l'entité Utilisateur
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class DemandeDonsController extends AbstractController
{
    /**
     * @Route("/demander_dons", name="demander_dons", methods={"POST"})
     */
    public function demanderDons(Request $request, EntityManagerInterface $entityManager): Response
    {
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
        }

        // Persistez la demande dans la base de données
        $entityManager->persist($demande);
        $entityManager->flush();

        // Rediriger ou retourner une réponse JSON, selon votre besoin
        return $this->render('demande_dons/demanderdons.html.twig');
    }
}
