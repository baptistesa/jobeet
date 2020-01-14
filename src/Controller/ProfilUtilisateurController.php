<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;

class ProfilUtilisateurController extends AbstractController
{

    public function displayProfilUtilisateur()
    {
        $session = new Session();
        $session->start();

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://ffb7c3a5.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $contents = $response->toArray();


        return $this->render('profil-utilisateur.html.twig', [
            "prenom" => $session->get('name'),
            "nom" => $session->get('last_name'),
            "mail" => $session->get('mail'),
            "id" => $session->get('id'),
            "description" => $session->get('description'),
            "utilisateurs" => $contents,
            "is_recruteur" => $session->get('is_recruteur'),
            "is_premium" => $session->get('is_premium')
        ]);
    }

    public function updateInfos(Request $request)
    {
        $session = new Session();
        $session->start();

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://ffb7c3a5.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $contents = $response->toArray();

        $index = 0;
        for ($i = 0; $i < count($contents["rows"]); $i++) {
            if ($contents["rows"][$i]["doc"]["_id"] == $session->get("id")) {
                $index = $i;
                if ($request->request->get('_prenom')) {
                    $contents["rows"][$i]["doc"]["name"] = $request->request->get('_prenom');
                    $session->set('name', $request->request->get('_prenom'));
                }
                if ($request->request->get('_nom')) {
                    $contents["rows"][$i]["doc"]["last_name"] = $request->request->get('_nom');
                    $session->set('last_name', $request->request->get('_nom'));
                }
                if ($request->request->get('_description')) {
                    $contents["rows"][$i]["doc"]["description"] = $request->request->get('_description');
                    $session->set('description', $request->request->get('_description'));
                }
                if ($request->request->get('_is_actif')) {
                    if ($request->request->get('_is_actif') == "oui") {
                        $contents["rows"][$i]["doc"]["is_actif"] = true;
                        $session->set('is_actif', true);
                    } else {
                        $contents["rows"][$i]["doc"]["is_actif"] = false;
                        $session->set('is_actif', false);
                    }
                }
                if ($request->request->get('_is_premium')) {
                    if ($request->request->get('_is_premium') == "oui") {
                        $contents["rows"][$i]["doc"]["is_premium"] = true;
                        $session->set('is_premium', true);
                    } else {
                        $contents["rows"][$i]["doc"]["is_premium"] = false;
                        $session->set('is_premium', false);
                    }
                }
            }
        }

        $response_update = $client->request('PUT', 'https://ffb7c3a5.ngrok.io/utilisateurs/' . $session->get("id"), [
            "headers" => [
                "Content-Type" => "application/json"
            ],
            'body' => json_encode($contents["rows"][$index]["doc"])
        ]);
        $contents_update = $response_update->toArray();


        return $this->render('profil-utilisateur.html.twig', [
            "prenom" => $session->get('name'),
            "nom" => $session->get('last_name'),
            "mail" => $session->get('mail'),
            "id" => $session->get('id'),
            "description" => $session->get('description'),
            "utilisateurs" => $contents,
            "is_recruteur" => $session->get('is_recruteur'),
            "is_premium" => $session->get('is_premium')
        ]);
    }
}
