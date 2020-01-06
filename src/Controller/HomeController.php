<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    public function displayHome() {
        return $this->render('home.html.twig');
    }

    public function goToLogin() {
        return $this->redirectToRoute('login');
    }

    public function goToRegister() {
        return $this->redirectToRoute('login');
    }

    public function goToProfilentreprise() {
        return $this->redirectToRoute('profil-entreprise');
    }
    
    public function goToListentreprise() {
        return $this->redirectToRoute('liste-entreprise');
    }
}