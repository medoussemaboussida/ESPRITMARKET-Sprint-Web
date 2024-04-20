<?php

namespace App\Controller;

use App\Entity\Codepromo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CodeType;
use App\Repository\CodeRepository;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Knp\Component\Pager\PaginatorInterface;

class CodeController extends AbstractController
{


/**#[Route('/ajouter-code', name: 'ajouter_code')]
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
}*/

private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

#[Route('/ajouter-code', name: 'ajouter_code')]
    public function ajouterCode(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $code = new Codepromo();
        $form = $this->createForm(CodeType::class, $code);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Enregistrez la catégorie dans la base de données
            $entityManager->persist($code);
            $entityManager->flush();

            $this->addFlash('success', 'Code promo ajoutée avec succès.');

            // Envoyer un e-mail à tous les utilisateurs
            $userEmails = $this->getAllUserEmails($entityManager); 

            foreach ($userEmails as $email) {
                $subject = "Nouveaux Code Promo Ajouté";
                $body = "Bonjour,\n\nUn Nouveau Code Promo a été ajouté dans notre épicerie. Le code est : " . $code->getCode() . ".\n\nCordialement,\nEsprit Market";
            
                $email = (new Email())
                    ->from('ghassenbenmahmoud3@gmail.com') // Adresse e-mail de l'expéditeur
                    ->to($email) // Adresse e-mail du destinataire
                    ->subject($subject)
                    ->text($body);
            
                // Envoyer l'e-mail
                $mailer->send($email);
            }
            

            // Redirigez l'utilisateur après l'ajout réussi
            return $this->redirectToRoute('afficher_codes');
        }

        // Affichez le formulaire
        $codes = $this->getDoctrine()->getRepository(Codepromo::class)->findAll();
     
        return $this->render('code/ajouter.html.twig', [
            'form' => $form->createView(),
            'codes' => $codes,
        ]);
    }

    private function getAllUserEmails(EntityManagerInterface $entityManager): array
    {
        $userRepository = $entityManager->getRepository(Utilisateur::class);
        $users = $userRepository->findAll();

        $emails = [];
        foreach ($users as $user) {
            $emails[] = $user->getEmailuser();
        }

        return $emails;
    }

#[Route('/afficher-codes', name: 'afficher_codes')]
    public function afficherCodes(Request $request,PaginatorInterface $paginator): Response
    {
        // Récupérer toutes les codes depuis la base de données
        $codes = $this->getDoctrine()->getRepository(Codepromo::class)->findAll();
         // Paginer les codes promo
        $codes = $paginator->paginate(
        $codes, // Données à paginer (ici, votre liste de codes promo)
        $request->query->getInt('page', 1), // Numéro de page par défaut
        2 // Nombre d'éléments par page
    );

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

    
#[Route('/afficher-codes', name: 'afficher_codes')]
public function afficherCodesfiltre(Request $request, CodeRepository $codeRepository): Response
{
    $searchQuery = $request->query->get('search_query');

    // Analysez la recherche de l'utilisateur
    $code = null;
    $reductionassocie = null;

    // Si une recherche est effectuée
    if ($searchQuery) {
        // Vérifiez si la recherche correspond à une réduction (uniquement des chiffres)
        if (ctype_digit($searchQuery)) {
            $reductionassocie = $searchQuery;
        } else {
            $code = $searchQuery;
        }
    }

    // Utilisez la méthode findByCriteria du repository pour rechercher les offres
    $codes = $codeRepository->findByCriteria($code, $reductionassocie);

    return $this->render('code/afficher.html.twig', [
        'codes' => $codes,
    ]);
}

#[Route('/afficher-codes', name: 'afficher_codes')]
public function findByCriteriaTriDate(Request $request, CodeRepository $codeRepository, $sort_by = 'datedebut'): Response
{
    $sortOrder = $request->query->get('sort_order', 'asc');

    // Vérifiez si l'ordre de tri est valide
    $validSortOrders = ['asc', 'desc'];
    if (!in_array($sortOrder, $validSortOrders)) {
        throw new \InvalidArgumentException('Invalid sort order.');
    }

    // Utilisez la méthode findByCriteria du repository pour rechercher les offres
    $codes = $codeRepository->findByCriteriaTriDate([], $sort_by, $sortOrder);

    return $this->render('code/afficher.html.twig', [
        'codes' => $codes,
        'sort_by' => $sort_by,
        'sort_order' => $sortOrder,
    ]);
}


#[Route('/afficher-codes', name: 'afficher_codes')]
public function findByCriteriaTriReduction(Request $request, CodeRepository $codeRepository, $sort_by = 'reductionassocie'): Response
{
    $sortOrder = $request->query->get('sort_order', 'asc');

    // Vérifiez si l'ordre de tri est valide
    $validSortOrders = ['asc', 'desc'];
    if (!in_array($sortOrder, $validSortOrders)) {
        throw new \InvalidArgumentException('Invalid sort order.');
    }

    // Utilisez la méthode findByCriteria du repository pour rechercher les offres
    $codes = $codeRepository->findByCriteriaTriReduction([], $sort_by, $sortOrder);

    return $this->render('code/afficher.html.twig', [
        'codes' => $codes,
        'sort_by' => $sort_by,
        'sort_order' => $sortOrder,
    ]);
}


}