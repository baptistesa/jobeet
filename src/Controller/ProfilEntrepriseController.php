<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\Session;

class ProfilEntrepriseController extends AbstractController
{

    public function displayProfilEntreprise($id) {
        $session = new Session();
        $session->start();


        $client = HttpClient::create();
        $response = $client->request('GET', 'https://3296c880.ngrok.io/entreprises/_all_docs?include_docs=true');
        $contents = $response->toArray();

        $response_annonces = $client->request('GET', "https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true");
        $contents_annonces = $response_annonces->toArray();

        return $this->render('profil-entreprise.html.twig', [ 
            'entreprises' => $contents,
            'annonces' => $contents_annonces,
            'id' => $id,
            'is_recruteur' => $session->get("is_recruteur")
        ]);
    }
}