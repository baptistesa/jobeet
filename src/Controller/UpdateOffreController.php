<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;

class UpdateOffreController extends AbstractController
{
    public function displayForm($offre_id)
    {
        $session = new Session();
        $session->start();

        return $this->render('update-annonce.html.twig', [
            "is_recruteur" => $session->get("is_recruteur"),
            "offre_id" => $offre_id
        ]);
    }

    public function submitForm(Request $request, $offre_id)
    {
        $session = new Session();
        $session->start();

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();



        $response2 = $client->request('GET', 'https://3296c880.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $contents2 = $response2->toArray();




        $title = "";
        $description = "";
        if ($request->request->get('_competences'))
            $competences = explode(' ', $request->request->get('_competences'));

        for ($l = 0; $l < count($contents["rows"]); $l++) {
            if ($contents["rows"][$l]["doc"]["_id"] == $offre_id) {
                if ($request->request->get('_title'))
                    $contents["rows"][$l]["doc"]["title"] = $request->request->get('_title');
                if ($request->request->get('_description'))
                    $contents["rows"][$l]["doc"]["description"] = $request->request->get('_description');
                if ($request->request->get('_competences'))
                    $contents["rows"][$l]["doc"]["competences"] = explode(' ', $request->request->get('_competences'));



                $matches = [];

                $total_competences = count($competences);
                for ($i = 0; $i < count($contents2["rows"]); ++$i) {
                    $current_id_user = $contents2["rows"][$i]["doc"]["_id"];

                    $comepetences_done = 0;
                    for ($j = 0; $j < count($contents2["rows"][$i]["doc"]["competences"]); $j++) {
                        for ($k = 0; $k < count($competences); $k++) {
                            if ($contents2["rows"][$i]["doc"]["competences"][$j] == $competences[$k])
                                $comepetences_done++;
                        }
                    }
                    $percent_match = $comepetences_done * 100 / $total_competences;
                    if ($percent_match >= 75 && $contents2["rows"][$i]["doc"]["is_actif"]) {
                        array_push($matches, (object) ['id_user' => $current_id_user, 'percent_match' => $percent_match]);
                    }
                }
                $contents["rows"][$l]["doc"]["matches"] = $matches;

                // var_dump($matches);

                $response_update = $client->request('PUT', 'https://3296c880.ngrok.io/annonces/' . $offre_id, [
                    "headers" => [
                        "Content-Type" => "application/json"
                    ],
                    'body' => json_encode($contents["rows"][$l]["doc"])
                ]);
                $contents_update = $response_update->toArray();

                
            }
        }
        return $this->render('update-annonce.html.twig', [
            "is_recruteur" => $session->get("is_recruteur"),
            "offre_id" => $offre_id
        ]);
    }
}
