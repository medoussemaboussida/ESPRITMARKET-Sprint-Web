<?php

namespace App\Controller;

use App\Entity\Codepromo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CodeType;


class CodeController extends AbstractController
{
#[Route('/ajouter-code', name: 'ajouter_code')]
    public function ajouterCode(Request $request): Response
{
    $code = new Codepromo();
    $form = $this->createForm(CodeType::class, $code);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        // Enregistrez la catégorie dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($code);
        $entityManager->flush();

        $this->addFlash('success', 'Code promo ajoutée avec succès.');

        // Redirigez l'utilisateur après l'ajout réussi
        return $this->redirectToRoute('afficher_codes');
    }
    //affichage
    $codes = $this->getDoctrine()->getRepository(Codepromo::class)->findAll();
 
    return $this->render('code/ajouter.html.twig', [
        'form' => $form->createView(),
        'codes' => $codes,

    ]);
}
#[Route('/afficher-codes', name: 'afficher_codes')]
    public function afficherCodes(): Response
    {
        // Récupérer toutes les codes depuis la base de données
        $codes = $this->getDoctrine()->getRepository(Codepromo::class)->findAll();

        return $this->render('code/afficher.html.twig', [
            'codes' => $codes,
        ]);
    }

    #[Route('/modifier-code/{id}', name: 'modifier_code')]
    public function modifierCode(int $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $code = $entityManager->getRepository(Codepromo::class)->find($id);
    
        // Vérifier si le code existe
        if (!$code) {
            throw $this->createNotFoundException('Aucun code trouvé pour cet identifiant.');
        }
    
        $form = $this->createForm(CodeType::class, $code); // Use CodeType form class here
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour le code dans la base de données
            $entityManager->flush();
    
            // Ajouter un message flash pour indiquer la modification réussie
            $this->addFlash('success', 'Code modifié avec succès.');
    
            // Rediriger vers la page d'affichage des codes
            return $this->redirectToRoute('afficher_codes');
        }
    
        return $this->render('code/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/consulter-code/{id}', name: 'consulter_code')]
    public function consulterCode(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $code = $entityManager->getRepository(Codepromo::class)->find($id);

        // Vérifier si le code existe
        if (!$code) {
            throw $this->createNotFoundException('Aucun code trouvée pour cet identifiant.');
        }
        return $this->render('code/consulter.html.twig', [
            'code' => $code,
        ]);
    }

    #[Route('/supprimer-code/{id}', name: 'supprimer_code')]
    public function supprimerCode(int $id): Response
    {
        // Récupérer l'offre à supprimer depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $code = $entityManager->getRepository(Codepromo::class)->find($id);

        // Vérifier si l'offre existe
        if (!$code) {
            throw $this->createNotFoundException('Aucun code promo trouvée pour cet identifiant.');
        }

        // Supprimer l'offre de la base de données
        $entityManager->remove($code);
        $entityManager->flush();

        // Ajouter un message flash pour indiquer la suppression réussie
        $this->addFlash('success', 'Code promo supprimée avec succès.');

        // Rediriger vers la page d'affichage des codes
        return $this->redirectToRoute('afficher_codes');
    }
}