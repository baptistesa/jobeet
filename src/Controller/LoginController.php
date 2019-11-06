<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LoginController extends AbstractController
{
    /**
     * @Route("/login")
     */
    public function login()
    {
        $number = random_int(0, 100);

        return $this->render('login.html.twig', [
            'number' => $number,
        ]);
    }
}