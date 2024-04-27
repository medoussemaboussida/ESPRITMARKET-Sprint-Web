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
use Symfony\Component\HttpFoundation\RedirectResponse;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): Response
    {
        return $this->render('utilisateur/login.html.twig');
    }

    #[Route('/login', name: 'app_login')]
    public function login(Request $request, SessionInterface $session): Response
    {
        // Récupérer les informations du formulaire
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // Récupérer l'entité Utilisateur à partir de l'email
        $user = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['emailuser' => $email, 'mdp' => $password]);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($user) {
            $iduser = $user->getIduser();
            $session->set('iduser', $iduser);
            
            // Rediriger en fonction du rôle de l'utilisateur
            if ($user->getRole() === 'Admin') {
                return $this->redirectToRoute('admin_dons'); // Redirection vers la page admin_page
            } elseif ($user->getRole() === 'Client') {
                return $this->redirectToRoute('dons_page'); // Redirection vers la page dons_page
            }
        } else {
            // Si les informations de connexion sont incorrectes, afficher un message d'erreur
            return $this->redirectToRoute('app_utilisateur');
        }
    }


    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): RedirectResponse
    {
        // Invalidater la session pour déconnecter l'utilisateur
        $session->invalidate();
    
        // Rediriger l'utilisateur vers la page de connexion
        return $this->redirectToRoute('app_utilisateur');
    }
}
