<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;

class ListeOffresController extends AbstractController
{

    public function displayListeOffres() {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://2ef89cb3.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        // dd($contents);
        return $this->render('liste-offres.html.twig', [
            'annonces' => $contents
        ]);
    }
}