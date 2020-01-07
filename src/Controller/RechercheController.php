<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RechercheController extends AbstractController
{

    public function displayRecherche() {
        return $this->render('recherche.html.twig');
    }
}