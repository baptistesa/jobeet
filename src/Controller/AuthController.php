<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{

    public function displayAuth() {
        return $this->render('auth.html.twig');
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