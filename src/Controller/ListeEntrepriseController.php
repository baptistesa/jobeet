<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\Session;

class ListeEntrepriseController extends AbstractController
{

    public function displayListeEntreprise()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://3296c880.ngrok.io/entreprises/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $session = new Session();
        $session->start();
        // dd($contents);
        return $this->render('liste-entreprise.html.twig', [
            'entreprises' => $contents,
            'is_recruteur' => $session->get("is_recruteur")
        ]);
    }
}
