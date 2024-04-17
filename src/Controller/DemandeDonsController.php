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
use App\Form\ModifierPointsFormType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\ChoiceType;
use Symfony\Component\Validator\Constraints\PositiveOrZero;





use App\Form\DemandedonsType; // Assurez-vous d'avoir ce fichier de formulaire



class DemandeDonsController extends AbstractController
{
    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }
    

    
    #[Route('/demander_dons/{idUser}', name: 'demander_dons', methods: ['GET', 'POST'])]
    public function demanderDons(Request $request, EntityManagerInterface $entityManager, $idUser, PaginatorInterface $paginator): Response
    {
        // Récupérer l'utilisateur spécifié par son ID depuis la base de données
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($idUser);
    
        // Si l'utilisateur n'existe pas, renvoyer une erreur
        if (!$utilisateur) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }
    
        $demande = new Demandedons();
        $form = $this->createForm(DemandedonsType::class, $demande);
    
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Assigner l'objet Utilisateur récupéré à la demande de don
            $demande->setIdUtilisateur($utilisateur);
            $demande->setNomuser($utilisateur->getNomuser());
            $demande->setPrenomuser($utilisateur->getPrenomuser());
    
            // Persistez la demande dans la base de données
            $entityManager->persist($demande);
            $entityManager->flush();
    
            // Rediriger vers la même page pour éviter la soumission répétée du formulaire
            return $this->redirectToRoute('demander_dons', ['idUser' => $idUser]);
        }
    
        // Paginer les résultats
        $query = $entityManager->getRepository(Demandedons::class)->createQueryBuilder('d')
            ->orderBy('d.datePublication', 'DESC')
            ->getQuery();
    
        $demandes = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );
    
        // Render the form and other data
        return $this->render('demande_dons/demanderdons.html.twig', [
            'utilisateur' => $utilisateur,
            'idUser' => $idUser,
            'form' => $form->createView(),
            'demandes' => $demandes,
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
    $this->addFlash('success', 'Les points ont été transférés avec succès.');


    // Rediriger vers la page demander_dons après le transfert
    return new JsonResponse(['success' => true, 'newPoints' => $newPoints]);
}



/**
 * @Route("/admin/demandedons", name="admin_demandedons")
 */
public function backDemandesDons(DemandedonsRepository $demandedonsRepository, Request $request): Response
{
    // Récupérer l'adresse e-mail saisie dans le formulaire
    $email = $request->query->get('email');

    // Si une adresse e-mail est saisie, filtrer les demandes de dons correspondantes
    if ($email) {
        $demandesDons = $demandedonsRepository->findByEmail($email);
    } else {
        // Sinon, récupérer toutes les demandes de dons
        $demandesDons = $demandedonsRepository->findAll();
    }

    // Rendre la vue Twig avec la liste des demandes de dons
    return $this->render('demande_dons/backDemandeDons.html.twig', [
        'Demandedons' => $demandesDons,
    ]);
}

/**
     * @Route("/admin/demandedons/{id}/delete", name="admin_demandedons_delete", methods={"GET", "POST"})
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



    /**
 * @Route("/modifier-points-demande/{id}", name="modifier_points_demande_don", methods={"GET", "POST"})
 */
public function modifierPointsDemandeDon(Request $request, int $id): Response
{
    // Récupérer la demande de don correspondant à l'identifiant
    $demandeDon = $this->getDoctrine()->getRepository(Demandedons::class)->find($id);

    // Vérifier si la demande de don existe
    if (!$demandeDon) {
        throw $this->createNotFoundException('La demande de don avec l\'identifiant '.$id.' n\'existe pas.');
    }

    // Récupérer l'utilisateur associé à la demande de don
    $utilisateur = $demandeDon->getIdUtilisateur();

    // Récupérer le nombre de points avant la modification de la demande de don
    $ancienPoints = $demandeDon->getNbpoints();

    // Créer le formulaire avec le type de formulaire ModifierPointsFormType
    $form = $this->createForm(ModifierPointsFormType::class, $demandeDon);

    // Traiter la soumission du formulaire
    $form->handleRequest($request);

    // Vérifier si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le nouveau nombre de points saisi dans le formulaire
        $nouveauPoints = $demandeDon->getNbpoints();

        // Calculer les points mis à jour
        $pointsMisAJour = $utilisateur->getNbPoints() + $ancienPoints - $nouveauPoints;

        // Mettre à jour le nombre de points de l'utilisateur
        $utilisateur->setNbPoints($pointsMisAJour);

        // Enregistrer les modifications dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Rafraîchir la page actuelle pour afficher les modifications
        return $this->redirectToRoute('admin_demandedons');
    }

    // Afficher le formulaire dans le template
    return $this->render('demande_dons/updatenbpointsdemande.html.twig', [
        'form' => $form->createView(),
        'demandeDon' => $demandeDon, // Passer la variable "demandeDon" au template
        'utilisateur' => $utilisateur, // Passer la variable "utilisateur" au template

    ]);
}



#[Route('/demander_dons_action', name: 'demander_dons_action', methods: ['GET', 'POST'])]
public function demanderDonsAction(Request $request): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    // Récupérer tous les utilisateurs
    $utilisateurs = $entityManager->getRepository(Utilisateur::class)->findAll();

    $form = $this->createFormBuilder()
    ->add('utilisateur', EntityType::class, [
        'class' => Utilisateur::class,
        'choice_label' => function ($utilisateur) {
            return $utilisateur->getNomUser() . ' ' . $utilisateur->getPrenomUser();
        },
    ])
        ->add('contenu', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le contenu est obligatoire.']),
                new Length([
                    'min' => 4,
                    'max' => 50,
                    'minMessage' => 'Le contenu doit contenir au moins {{ limit }} mots.',
                    'maxMessage' => 'Le contenu ne peut pas contenir plus de {{ limit }} mots.'
                ]),
            ],
        ])
        ->add('objectifPoints', IntegerType::class, [
            'constraints' => [
                new NotBlank(['message' => "L'objectif de points est obligatoire."]),
                new Positive(['message' => "L'objectif de points doit être positif."]),
            ],
        ])
        ->add('delai', DateType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le délai est obligatoire.']),
                new GreaterThan([
                    'value' => new \DateTime(),
                    'message' => 'Le délai doit être postérieur à la date d\'aujourd\'hui.'
                ]),
            ],
        ])
        ->getForm();

    // Gérer la soumission du formulaire
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer les données du formulaire
        $data = $form->getData();

        // Récupérer l'utilisateur sélectionné
        $utilisateur = $data['utilisateur'];

        // Créer une nouvelle demande
        $demande = new Demandedons();
        $demande->setIdUtilisateur($utilisateur);
        $demande->setNomPrenomUtilisateur($nomCompletUtilisateur); // Champ pour le nom complet de l'utilisateur

        $demande->setContenu($data['contenu']);
        $demande->setObjectifPoints($data['objectifPoints']);
        $demande->setDelai($data['delai']);

        // Récupérer le prénom de l'utilisateur
        $nomCompletUtilisateur = $utilisateur->getNomUser() . ' ' . $utilisateur->getPrenomUser();

    

        // Persistez la demande dans la base de données
        $entityManager->persist($demande);
        $entityManager->flush();

        // Rediriger vers la même page pour éviter la soumission répétée du formulaire
        return $this->redirectToRoute('admin_demandedons');
    }

    return $this->render('demande_dons/ajouterdemandedons.html.twig', [
        'utilisateurs' => $utilisateurs,
        'form' => $form->createView(),
    ]);
}

}