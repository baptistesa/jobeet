<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;

class ProfilEntrepriseController extends AbstractController
{

    public function displayProfilEntreprise($id) {
        // dd($id);
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://2ef89cb3.ngrok.io/entreprises/_all_docs?include_docs=true');
        $contents = $response->toArray();
        return $this->render('profil-entreprise.html.twig', [ 
            'entreprises' => $contents,
            'id' => $id
        ]);
    }
}