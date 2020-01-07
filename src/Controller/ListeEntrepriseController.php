<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;

class ListeEntrepriseController extends AbstractController
{

    public function displayListeEntreprise()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://9350ba5d.ngrok.io/entreprises/_all_docs?include_docs=true');
        $contents = $response->toArray();
        // dd($contents);
        return $this->render('liste-entreprise.html.twig', [
            'entreprises' => $contents
        ]);
    }
}
