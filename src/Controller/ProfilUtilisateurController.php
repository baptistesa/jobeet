<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;

class ProfilUtilisateurController extends AbstractController
{

    public function displayProfilUtilisateur() {
        $session = new Session();
        $session->start();
        
        return $this->render('profil-utilisateur.html.twig', [
            "prenom" => $session->get('name'),
            "nom" => $session->get('last_name'),
            "mail" => $session->get('mail'),
            "description" => $session->get('description')
        ]);
    }
}