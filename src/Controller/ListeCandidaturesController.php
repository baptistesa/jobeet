<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListeCandidaturesController extends AbstractController
{

    public function displayListeCandidatures() {
        return $this->render('liste-candidatures.html.twig');
    }
}