<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\Session;


class CreateAnnonceController extends AbstractController
{
    public function displayForm(Request $request)
    {
        $session = new Session();
        $session->start();

        return $this->render('create-annonce.html.twig', array('display_info' => false, 'is_recruteur' => $session->get("is_recruteur")));
    }

    public function postForm(Request $request)
    {
        $session = new Session();
        $session->start();

        $title = $request->request->get('_title');
        $description = $request->request->get('_description');
        $competences = explode(' ', $request->request->get('_competences'));


        $client_user = HttpClient::create();
        $response_user = $client_user->request('GET', 'https://ffb7c3a5.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $contents_user = $response_user->toArray();

        $matches = [];

        $total_competences = count($competences);
        for ($i = 0; $i < count($contents_user["rows"]); ++$i) {
            $current_id_user = $contents_user["rows"][$i]["doc"]["_id"];

            $comepetences_done = 0;
            for ($j = 0; $j < count($contents_user["rows"][$i]["doc"]["competences"]); $j++) {
                for ($k = 0; $k < count($competences); $k++) {
                    if ($contents_user["rows"][$i]["doc"]["competences"][$j] == $competences[$k])
                        $comepetences_done++;
                }
            }
            $percent_match = $comepetences_done * 100 / $total_competences;
            if ($percent_match >= 75 && $contents_user["rows"][$i]["doc"]["is_actif"]) {
                array_push($matches, (object) ['id_user' => $current_id_user, 'percent_match' => $percent_match]);
            }
        }


        $annonce = array(
            'title' => $title,
            'description' => $description,
            'competences' => $competences,
            'entreprise_id' => $session->get("entreprise_id"),
            'recruteur_id' => $session->get("id"),
            "postulants_acceptes" => [],
            "postulants_refuses" => [],
            "postulants" => [],
            "matches" => $matches
        );

        $client = HttpClient::create();
        $response = $client->request('POST', 'https://ffb7c3a5.ngrok.io/annonces', [
            "headers" => [
                "Content-Type" => "application/json"
            ],
            'body' => json_encode($annonce)
        ]);
        $contents = $response->toArray();

        return $this->render('create-annonce.html.twig', array('display_info' => true, 'is_recruteur' => $session->get("is_recruteur")));
    }
}
