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
        $response = $client->request('GET', 'https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://3296c880.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $respentreprise = $client->request('GET', 'https://3296c880.ngrok.io/entreprises/_all_docs?include_docs=true');
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

    public function deleteOffre($id)
    {
        $session = new Session();
        $session->start();

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://3296c880.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $respentreprise = $client->request('GET', 'https://3296c880.ngrok.io/entreprises/_all_docs?include_docs=true');
        $entreprises = $respentreprise->toArray();

        for ($i = 0; $i < count($contents["rows"]); $i++)
            if ($contents["rows"][$i]["doc"]["_id"] == $id) {
                $rev = $contents["rows"][$i]["doc"]["_rev"];
                for ($k = 0; $k < count($contents["rows"][$i]["doc"]["postulants"]); $k++) {
                    $data = array(
                        'type' => "suppression",
                        'id_user' => $contents["rows"][$i]["doc"]["postulants"][$k],
                        'message' => "L'annonce '". $contents["rows"][$i]["doc"]["title"] ."' a été supprimée"
                    );

                    $add_notif_response = $client->request('POST', 'https://3296c880.ngrok.io/notifications', [
                        "headers" => [
                            "Content-Type" => "application/json"
                        ],
                        "body" => json_encode($data)
                    ]);
                    $add_notif_content = $add_notif_response->toArray();
                }
            }

        $response_delete = $client->request('DELETE', 'https://3296c880.ngrok.io/annonces/' . $id . "/", [
            'query' => [
                'rev' => $rev
            ]
        ]);
        $contents_delete = $response->toArray();

        $response_all_annonces = $client->request('GET', 'https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents_all_annonce = $response_all_annonces->toArray();



        return $this->render('mesoffres.html.twig', [
            'annonces' => $contents_all_annonce,
            'users' => $users,
            'entreprises' => $entreprises,
            "is_recruteur" => $session->get('is_recruteur'),
            "id_current_user" => $session->get("id")
        ]);
    }
}
