<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;

class ProfilEntrepriseController extends AbstractController
{

    public function displayProfilEntreprise($id) {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://2ef89cb3.ngrok.io/entreprises/_all_docs?include_docs=true');
        $contents = $response->toArray();

        $response_annonces = $client->request('GET', "https://2ef89cb3.ngrok.io/annonces/_all_docs?include_docs=true");
        $contents_annonces = $response_annonces->toArray();

        return $this->render('profil-entreprise.html.twig', [ 
            'entreprises' => $contents,
            'annonces' => $contents_annonces,
            'id' => $id
        ]);
    }
}