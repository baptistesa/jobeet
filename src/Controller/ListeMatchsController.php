<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\Session;

class ListeMatchsController extends AbstractController
{

    public function displayListeMatchs()
    {
        $session = new Session();
        $session->start();
        if ($session->get("is_recruteur"))
            return $this->displayRecruteur($session);
        else
            return $this->displayUser($session);
    }

    public function displayUser($session)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://20678575.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();

        $annonces = [];
        for ($i = 0; $i < count($contents["rows"]); $i++) {
            for ($j = 0; $j < count($contents["rows"][$i]["doc"]["matches"]); $j++) {
                if ($contents["rows"][$i]["doc"]["matches"][$j]["id_user"] == $session->get("id")) {
                    array_push($annonces, (object) ['annonce' => $contents["rows"][$i]["doc"], 'percent_match' => $contents["rows"][$i]["doc"]["matches"][$j]["percent_match"]]);
                }
            }
        }
        return $this->render('liste-matchs.html.twig', array("is_recruteur" => false, "annonces" => $annonces));
    }

    public function displayRecruteur($session)
    {
        return $this->render('liste-matchs.html.twig', array("is_recruteur" => true));
    }
}
