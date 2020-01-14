<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\Session;

class OffreController extends AbstractController
{

    public function displayOffre($id)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://d10080de.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://d10080de.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $annonce_finale = null;
        $invited = [];

        $session = new Session();
        $session->start();

        foreach ($contents["rows"] as $annonce) {
            if ($annonce["doc"]["_id"] == $id) {
                $annonce_finale = $annonce;
                foreach ($annonce["doc"]["matches"] as $matchs) {
                    if (in_array($matchs["id_user"], $annonce["doc"]["postulants"]) == false)
                        $invited[] = $matchs["id_user"];
                }
            }
        }
        return $this->render('offre.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'inviteds' => $invited,
            'id' => $id,
            'is_recruteur' => $session->get('is_recruteur')
        ]);
    }

    public function postuler($id)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://d10080de.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://d10080de.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $annonce_finale = null;
        $invited = [];

        $session = new Session();
        $session->start();

        foreach ($contents["rows"] as $annonce) {
            if ($annonce["doc"]["_id"] == $id) {
                if (in_array($session->get('id'), $annonce["doc"]["postulants"]) == false)
                    array_push($annonce["doc"]["postulants"], $session->get('id'));
                $annonce_finale = $annonce;
                foreach ($annonce["doc"]["matches"] as $matchs) {
                    if (in_array($matchs["id_user"], $annonce["doc"]["postulants"]) == false)
                        $invited[] = $matchs["id_user"];
                }
                $response_update = $client->request('PUT', 'https://d10080de.ngrok.io/annonces/' . $id, [
                    "headers" => [
                        "Content-Type" => "application/json"
                    ],
                    'body' => json_encode($annonce["doc"])
                ]);
                $contents_update = $response_update->toArray();

                $response = $client->request('GET', 'https://d10080de.ngrok.io/annonces/_all_docs?include_docs=true');
                $contents = $response->toArray();
            }
        }

        return $this->render('offre.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'inviteds' => $invited,
            'id' => $id,
            'is_recruteur' => $session->get('is_recruteur')
        ]);
    }

    public function accepter($id, $offre_id)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://d10080de.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://d10080de.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $annonce_finale = null;
        $invited = [];

        $session = new Session();
        $session->start();

        foreach ($contents["rows"] as $annonce) {
            if ($annonce["doc"]["_id"] == $offre_id) {
                if (in_array($id, $annonce["doc"]["postulants_acceptes"]) == false)
                    array_push($annonce["doc"]["postulants_acceptes"], $id);
                $annonce_finale = $annonce;
                foreach ($annonce["doc"]["matches"] as $matchs) {
                    if (in_array($matchs["id_user"], $annonce["doc"]["postulants"]) == false)
                        $invited[] = $matchs["id_user"];
                }
                $response_update = $client->request('PUT', 'https://d10080de.ngrok.io/annonces/' . $offre_id, [
                    "headers" => [
                        "Content-Type" => "application/json"
                    ],
                    'body' => json_encode($annonce["doc"])
                ]);
                $contents_update = $response_update->toArray();

                $response = $client->request('GET', 'https://d10080de.ngrok.io/annonces/_all_docs?include_docs=true');
                $contents = $response->toArray();
            }
        }

        return $this->render('offre.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'inviteds' => $invited,
            'id' => $offre_id,
            'is_recruteur' => $session->get('is_recruteur')
        ]);
    }

    public function refuser()
    {
    }
}
