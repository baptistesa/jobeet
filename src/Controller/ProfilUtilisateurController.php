<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilUtilisateurController extends AbstractController
{

    public function displayProfilUtilisateur() {
        return $this->render('profil-utilisateur.html.twig');
    }
}