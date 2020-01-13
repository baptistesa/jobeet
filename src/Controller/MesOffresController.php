<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\Session;

class MesOffresController extends AbstractController
{

    public function displayListeOffres()
    {

        $session = new Session();
        $session->start();

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://d10080de.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://d10080de.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $respentreprise = $client->request('GET', 'https://d10080de.ngrok.io/entreprises/_all_docs?include_docs=true');
        $entreprises = $respentreprise->toArray();
        // dd($contents);
        return $this->render('mesoffres.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'entreprises' => $entreprises,
            "is_recruteur" => $session->get('is_recruteur'),
            "id_current_user" => $session->get("id")
        ]);
    }
}
