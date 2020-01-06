<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilEntrepriseController extends AbstractController
{

    public function displayProfilEntreprise() {
        return $this->render('profil-entreprise.html.twig');
    }
}