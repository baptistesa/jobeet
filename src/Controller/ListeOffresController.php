<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\Session;

class ListeOffresController extends AbstractController
{

    public function displayListeOffres() {
        
        $session = new Session();
        $session->start();

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://ffb7c3a5.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://ffb7c3a5.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $respentreprise = $client->request('GET', 'https://ffb7c3a5.ngrok.io/entreprises/_all_docs?include_docs=true');
        $entreprises = $respentreprise->toArray();
        // dd($contents);
        return $this->render('liste-offres.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'entreprises' => $entreprises,
            "is_recruteur" => $session->get('is_recruteur')
        ]);
    }
}