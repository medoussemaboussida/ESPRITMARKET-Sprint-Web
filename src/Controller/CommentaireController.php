<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Entity\Utilisateur; // Ajoutez ceci
use Symfony\Component\HttpFoundation\Request; // Importer la classe Request correcte
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{
    /**
     * @Route("/ajouter-commentaire", name="ajouter_commentaire", methods={"POST"})
     */
    public function ajouterCommentaire(Request $request): Response
    {
        $contenu = $request->request->get('comment');
        $publicationId = $request->request->get('publication_id');
        $userId = 1; // ID de l'utilisateur

        // Récupérer la publication en fonction de son ID
        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($publicationId);

        if (!$publication) {
            throw $this->createNotFoundException('La publication avec l\'ID '.$publicationId.' n\'existe pas.');
        }

        // Récupérer l'utilisateur en fonction de son ID
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($userId);

        if (!$utilisateur) {
            throw $this->createNotFoundException('Aucun utilisateur trouvé avec l\'ID '.$userId.'.');
        }

        // Créer un nouveau commentaire
        $commentaire = new Commentaire();

        // Vérifier si le contenu du commentaire est valide
        if ($contenu !== null) {
            $commentaire->setDescriptioncommentaire($contenu);
        } else {
            $commentaire->setDescriptioncommentaire(''); // Ou une autre valeur par défaut
        }

        // Définir la publication pour ce commentaire
        $commentaire->setIdpublication($publication);

        // Définir l'utilisateur pour ce commentaire
        $commentaire->setIduser($utilisateur);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('front_publication_detail', [
            'id' => $publicationId,
            'iduser' => $userId, // Assurez-vous de remplacer $userId par la variable contenant l'ID de l'utilisateur
        ]);    }

    /**
     * @Route("/publication/{id}/commentaire", name="detail_commentaire")
     */
    public function detailCommentaire(Publication $publication): Response
    {
        // Récupérer les commentaires associés à cette publication
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findBy(['idpublication' => $publication]);

        return $this->render('publication/detailCommentaire.html.twig', [
            'publication' => $publication,
            'commentaires' => $commentaires,
        ]);
    }


    /**
 * @Route("/commentaire/{id}/delete", name="delete_commentaire", methods={"DELETE"})
 */
public function deleteCommentaire(Commentaire $commentaire): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($commentaire);
    $entityManager->flush();

    $this->addFlash('success', 'Le commentaire a été supprimé avec succès.');

    // Rediriger vers la page où vous affichez les commentaires
    return $this->redirectToRoute('detail_commentaire', ['id' => $commentaire->getIdpublication()->getIdPublication()]);
}
}
