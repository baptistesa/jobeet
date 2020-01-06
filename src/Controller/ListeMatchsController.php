<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListeMatchsController extends AbstractController
{

    public function displayListeMatchs() {
        return $this->render('liste-matchs.html.twig');
    }
}