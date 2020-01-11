<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;

class NotificationsController extends AbstractController
{
    public function displayPage()
    {
        
        $session = new Session();
        $session->start();

        return $this->render('notifications.html.twig', [
            'is_recruteur' => $session->get("is_recruteur")
        ]);
    }

}