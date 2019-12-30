<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SignupController extends AbstractController
{
    public function signup()
    {
        return $this->render('signup.html.twig');
    }

}