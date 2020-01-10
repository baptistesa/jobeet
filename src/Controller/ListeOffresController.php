<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;

class ListeOffresController extends AbstractController
{

    public function displayListeOffres() {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://56035fdf.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://56035fdf.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $respentreprise = $client->request('GET', 'https://56035fdf.ngrok.io/entreprises/_all_docs?include_docs=true');
        $entreprises = $respentreprise->toArray();
        // dd($contents);
        return $this->render('liste-offres.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'entreprises' => $entreprises
        ]);
    }
}