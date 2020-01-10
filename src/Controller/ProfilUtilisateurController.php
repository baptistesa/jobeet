<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpClient\HttpClient;

class ProfilUtilisateurController extends AbstractController
{

    public function displayProfilUtilisateur()
    {
        $session = new Session();
        $session->start();

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://56035fdf.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $contents = $response->toArray();


        return $this->render('profil-utilisateur.html.twig', [
            "prenom" => $session->get('name'),
            "nom" => $session->get('last_name'),
            "mail" => $session->get('mail'),
            "id" => $session->get('id'),
            "description" => $session->get('description'),
            "utilisateurs" => $contents
        ]);
    }
}
