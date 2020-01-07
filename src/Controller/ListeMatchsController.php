<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;

class ListeMatchsController extends AbstractController
{

    public function displayListeMatchs() {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://2ef89cb3.ngrok.io/matches/_all_docs?include_docs=true');
        $contents = $response->toArray();
        // dd($contents);
        return $this->render('liste-match.html.twig', [
            'matches' => $contents
        ]);
    }
}