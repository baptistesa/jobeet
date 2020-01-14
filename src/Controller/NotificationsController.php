<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpClient\HttpClient;

class NotificationsController extends AbstractController
{
    public function displayPage()
    {
        
        $session = new Session();
        $session->start();

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://ffb7c3a5.ngrok.io/notifications/_all_docs?include_docs=true');
        $content = $response->toArray();

        return $this->render('notifications.html.twig', [
            'is_recruteur' => $session->get("is_recruteur"),
            'notifications' => $content,
            'id' => $session->get("id")
        ]);
    }

}