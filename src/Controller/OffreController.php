<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;

class OffreController extends AbstractController
{

    public function displayOffre($id) {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://2ef89cb3.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://2ef89cb3.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        return $this->render('offre.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'id' => $id
        ]);
    }
}