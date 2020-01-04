<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilentrepriseController extends AbstractController
{

    public function displayProfilentreprise() {
        return $this->render('profil-entreprise.html.twig');
    }
}