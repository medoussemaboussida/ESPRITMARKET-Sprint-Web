<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): Response
    {
        return $this->render('utilisateur/login.html.twig', [
            
        ]);
    }
    #[Route('/login', name: 'app_login')]
public function login(Request $request, AuthenticationUtils $authenticationUtils, SessionInterface $session): Response
{
   // Récupérer les informations du formulaire
   $email = $request->request->get('email');
   $password = $request->request->get('password');

   // Récupérer l'entité Utilisateur à partir de l'email
   $user = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['emailuser' => $email]);
   $user2 = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['mdp' => $password]);

   // Vérifier si l'utilisateur existe et si le mot de passe est correct
   if ($user && $user2) {
    $iduser = $user->getIduser();
       $session->set('iduser', $iduser);
       return $this->redirectToRoute('app_produit');
   } else {
       // Si les informations de connexion sont incorrectes, afficher un message d'erreur
       return $this->redirectToRoute('app_utilisateur');   }
}
private $logoutUrlGenerator;

    public function __construct(LogoutUrlGenerator $logoutUrlGenerator)
    {
        $this->logoutUrlGenerator = $logoutUrlGenerator;
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode sera vide car le logout est géré par Symfony
    }
}
