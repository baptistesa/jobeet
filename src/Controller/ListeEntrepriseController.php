<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListeEntrepriseController extends AbstractController
{

    public function displayListeEntreprise() {
        return $this->render('liste-entreprise.html.twig');
    }
}