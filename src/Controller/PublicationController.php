<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Publication;
use App\Entity\Utilisateur;
use App\Entity\Commentaire;
use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Security;
use setasign\Fpdi\JsPdf;
use setasign\Fpdi\Fpdf\Tcpdf;
use setasign\Fpdi\Fpdf\Tcpdf\Fpdi;
use Dompdf\Dompdf;
use Dompdf\Options;


class PublicationController extends AbstractController
{
    /**
     * @Route("/publication", name="app_publication")
     */
    public function index(PublicationRepository $publicationRepository): Response
    {
        $publications = $publicationRepository->findAll();

        // Récupérer le chemin vers le dossier des images depuis les paramètres
        $imagesDirectory = $this->getParameter('images_directory');

        // Ajouter le chemin des images à chaque publication
        foreach ($publications as $publication) {
            $publication->imagePath = $imagesDirectory . '/' . $publication->getImagePublication();
        }

        return $this->render('publication/listPublication.html.twig', [
            'publications' => $publications,
        ]);
    }

    /**
     * @Route("/publication/add", name="add_publication", methods={"GET", "POST"})
     */
    public function addPublication(Request $request): Response
    {
        $publication = new Publication();

        // Créer le formulaire
        $form = $this->createForm(PublicationType::class, $publication);

        // Manipuler la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le fichier de l'image
            $imageFile = $form->get('imagePublication')->getData();

            // Vérifier si un fichier a été téléchargé
            if ($imageFile) {
                // Générer un nom unique pour l'image
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Déplacer le fichier vers le répertoire des uploads
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'erreur si le déplacement du fichier échoue
                }

                // Mettre à jour le nom de l'image dans l'entité Publication
                $publication->setImagePublication($newFilename);
            }

            // Définir la date de publication sur la date actuelle
            $publication->setDatePublication(new \DateTime());

            // EntityManager pour persister l'entité
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($publication);
            $entityManager->flush();

            // Redirection après l'ajout
            return $this->redirectToRoute('app_publication');
        }

        // Rendre le formulaire
        return $this->render('publication/addPublication.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/publication/cancel", name="cancel_publication")
     */
    public function cancelPublication(): Response
    {
        // Créer une nouvelle instance de Publication pour réinitialiser les valeurs
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);

        return $this->render('publication/addPublication.html.twig', [
            'form' => $form->createView(),
        ]);
    }

   /**
 * @Route("/publication/{id}", name="delete_publication", methods={"DELETE"})
 */
public function deletePublication(Request $request, Publication $publication): Response
{
    if ($this->isCsrfTokenValid('delete' . $publication->getIdPublication(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();

        // Récupérer les commentaires liés à cette publication
        $commentaires = $entityManager->getRepository(Commentaire::class)->findBy(['idpublication' => $publication->getIdPublication()]);

        // Supprimer chaque commentaire lié
        foreach ($commentaires as $commentaire) {
            $entityManager->remove($commentaire);
        }

        // Supprimer la publication
        $entityManager->remove($publication);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_publication');
}


/**
 * @Route("/publication/{id}/edit", name="edit_publication", methods={"GET", "POST"})
 */
public function editPublication(Request $request, Publication $publication): Response
{
    $form = $this->createForm(PublicationType::class, $publication);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le fichier de la nouvelle image
        $imageFile = $form->get('imagePublication')->getData();
        
        // Vérifier si un nouveau fichier a été téléchargé
        if ($imageFile) {
            // Générer un nom unique pour la nouvelle image
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            // Déplacer le fichier vers le répertoire des uploads
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer l'erreur si le déplacement du fichier échoue
            }

            // Mettre à jour le nom de l'image dans l'entité Publication
            $publication->setImagePublication($newFilename);
        }

        // Enregistrer les modifications dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Rediriger vers la page de la liste des publications
        return $this->redirectToRoute('app_publication');
    }

    // Rendre le formulaire
    return $this->render('publication/editPublication.html.twig', [
        'form' => $form->createView(),
        'publication' => $publication, // Passer l'entité Publication au template
    ]);
}


/**
     * @Route("/publication/reset", name="reset_publication")
     */
    public function resetPublication(): Response
    {
        // Créer une nouvelle instance de Publication pour réinitialiser les valeurs
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);

        return $this->render('publication/editPublication.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
 * @Route("/publications", name="search_publications", methods={"GET"})
 */
public function searchPublications(Request $request, PublicationRepository $publicationRepository): Response
{
    $searchTerm = $request->query->get('search');

    // Si un terme de recherche est fourni, filtrez les publications
    if ($searchTerm) {
        $publications = $publicationRepository->findBySearchTerm($searchTerm);
    } else {
        // Sinon, récupérez toutes les publications
        $publications = $publicationRepository->findAll();
    }

    return $this->render('publication/listPublication.html.twig', [
        'publications' => $publications,
    ]);
}

    /**
     * @Route("/publication/frontPublication/{iduser}", name="front_publication")
     */
    public function frontPublications($iduser): Response
    {
        $publications = $this->getDoctrine()->getRepository(Publication::class)->findAll();

        // Récupérer l'utilisateur en fonction de son ID
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($iduser);

        return $this->render('publication/frontPublication.html.twig', [
            'publications' => $publications,
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/publication/{id}/detail/{iduser}", name="front_publication_detail")
     */
    public function detailPublication($id, $iduser): Response
    {
        // Récupérer la publication en fonction de son ID depuis la base de données
        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($id);

        // Vérifier si la publication existe
        if (!$publication) {
            throw $this->createNotFoundException('La publication avec l\'ID '.$id.' n\'existe pas.');
        }

        // Récupérer l'utilisateur en fonction de son ID
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($iduser);

        // Récupérer les commentaires associés à cette publication
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findBy(['idpublication' => $publication]);

        // Rendre la vue detailPublication.html.twig avec les détails de la publication et les commentaires
        return $this->render('publication/detailPublication.html.twig', [
            'publication' => $publication,
            'commentaires' => $commentaires,
            'utilisateur' => $utilisateur,
        ]);
    }


    /**
 * @Route("/commentaire/{id}/supprimer", name="supprimer_commentaire", methods={"DELETE"})
 */
public function supprimerCommentaire(Commentaire $commentaire): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($commentaire);
    $entityManager->flush();

    $this->addFlash('success', 'Le commentaire a été supprimé avec succès.');

    // Récupérer l'ID de la publication
    $publicationId = $commentaire->getIdpublication()->getIdPublication();

    // Récupérer l'ID de l'utilisateur associé au commentaire
    $userId = $commentaire->getIduser()->getIduser();

    // Rediriger vers la page où vous affichez les commentaires avec les deux IDs
    return $this->redirectToRoute('front_publication_detail', [
        'id' => $publicationId,
        'iduser' => $userId
    ]);
}

/**
 * @Route("/commentaire/{id}/modifier", name="modifier_commentaire", methods={"POST"})
 */
public function modifierCommentaire(Commentaire $commentaire, Request $request): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    // Récupérer la nouvelle description du commentaire à partir de la requête
    $nouvelleDescription = $request->request->get('descriptioncommentaire');

    // Mettre à jour la description du commentaire
    $commentaire->setDescriptioncommentaire($nouvelleDescription);
    $userId = $commentaire->getIduser()->getIduser();

    // Enregistrer les changements en base de données
    $entityManager->flush();

    // Rediriger vers une autre page après la modification, par exemple la page de détails de la publication
    return $this->redirectToRoute('front_publication_detail', ['id' => $commentaire->getIdpublication()->getIdpublication() , 'iduser' => $userId]);
}

/**
 * @Route("/liste_publications", name="liste_publications")
 */
public function listePublications(Request $request, PublicationRepository $publicationRepository): Response
{
    $tri = $request->query->get('tri_date');

    $publications = $publicationRepository->findAll();

    if ($tri === 'asc') {
        usort($publications, function($a, $b) {
            return $a->getDatePublication() <=> $b->getDatePublication();
        });
    } elseif ($tri === 'desc') {
        usort($publications, function($a, $b) {
            return $b->getDatePublication() <=> $a->getDatePublication();
        });
    }

    return $this->render('publication/listPublication.html.twig', [
        'publications' => $publications,
    ]);
}




/**
     * @Route("/publications", name="publications")
     */
    public function publications(): Response
    {
        // Récupérer toutes les publications avec les commentaires
        $publications = $this->getDoctrine()->getRepository(Publication::class)->findAll();

        // Chaque publication doit avoir ses commentaires chargés
        foreach ($publications as $publication) {
            $commentaires = $publication->getCommentaires();
            $publication->commentairesCount = count($commentaires);
        }

        return $this->render('publication/listPublication.html.twig', [
            'publications' => $publications,
        ]);
    }


    /**
 * @Route("/publication/statistiques-publications", name="statistiques_publications")
 */
public function statistiquesPublications(): Response
{
    // Récupérer toutes les publications avec leurs commentaires
    $publications = $this->getDoctrine()->getRepository(Publication::class)->findAll();

    // Récupérer le nombre de commentaires pour chaque publication
    $publicationData = [];
    foreach ($publications as $publication) {
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findBy(['idpublication' => $publication->getIdpublication()]);
        $commentairesCount = count($commentaires);
        
        $publicationData[] = [
            'titrePublication' => $publication->getTitrePublication(),
            'commentairesCount' => $commentairesCount,
        ];
    }

    // Passer les données au template Twig
    return $this->render('publication/staticPublication.html.twig', [
        'publicationData' => $publicationData,
    ]);
}

/**
 * @Route("/bar-chart-publications", name="bar_chart_publications")
 */
public function barChartPublications(): Response
{
    // Récupérer toutes les publications avec les commentaires
    $publications = $this->getDoctrine()->getRepository(Publication::class)->findAll();

    // Chaque publication doit avoir ses commentaires chargés
    foreach ($publications as $publication) {
        $commentaires = $publication->getCommentaires();
        $publication->commentairesCount = count($commentaires);
    }

    // Extraire les titres des publications et les nombres de commentaires
    $publicationData = [];
    foreach ($publications as $publication) {
        $publicationData[] = [
            'titrePublication' => $publication->getTitrePublication(),
            'commentairesCount' => $publication->commentairesCount,
        ];
    }

    // Passer les données au template Twig
    return $this->render('publication/staticPublication.html.twig', [
        'publicationData' => $publicationData,
    ]);
}


/**
 * @Route("/generate-pdf", name="generate_pdf")
 */
public function generatePdf(): Response
{
    try {
        $entityManager = $this->getDoctrine()->getManager();
        $publications = $entityManager->getRepository(Publication::class)->findAll();

        // Vérifier si des publications existent
        if (empty($publications)) {
            throw new \Exception('Aucune publication à afficher dans le PDF.');
        }

        // Regrouper les publications par mois
        $publicationsByMonth = [];
        foreach ($publications as $publication) {
            $monthYear = $publication->getDatePublication()->format('F Y');
            $publicationsByMonth[$monthYear][] = $publication;
        }

        // Configuration de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        // Créer une instance de Dompdf
        $dompdf = new Dompdf($options);

        // Générer le contenu HTML à partir du template Twig
        $html = $this->renderView('pdf/publications.html.twig', [
            'publicationsByMonth' => $publicationsByMonth,
        ]);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // (Optionnel) Réglez la taille du papier et l'orientation
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF
        $dompdf->render();

        // Générer le contenu du PDF
        $content = $dompdf->output();

        // Réponse HTTP avec le PDF en tant que téléchargement
        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="liste_publications.pdf"');

        return $response;
    } catch (\Exception $e) {
        // Enregistrer l'erreur dans les logs
        $errorMessage = 'Erreur lors de la génération du PDF : ' . $e->getMessage();
        $this->get('logger')->error($errorMessage);

        // Répondre avec une erreur
        return new Response($errorMessage, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
}

