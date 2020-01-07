<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListeOffresController extends AbstractController
{

    public function displayListeOffres() {
        return $this->render('liste-offres.html.twig');
    }
}