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

}