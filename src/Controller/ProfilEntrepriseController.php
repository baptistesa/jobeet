<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilEntrepriseController extends AbstractController
{

    public function displayProfilEntreprise($id) {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://9350ba5d.ngrok.io/entreprises/_all_docs?include_docs=true');
        $contents = $response->toArray();
        return $this->render('profil-entreprise.html.twig', [ 
            'entreprises' => $contents,
            'id' => $id
        ]);
    }
}