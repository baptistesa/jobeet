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
        $response = $client->request('GET', 'https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://3296c880.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $annonce_finale = null;
        $invited = [];

        $session = new Session();
        $session->start();

        $pourcentage = 0;
        $classement = 1;

        foreach ($contents["rows"] as $annonce) {
            if ($annonce["doc"]["_id"] == $id) {
                $annonce_finale = $annonce;
                foreach ($annonce["doc"]["matches"] as $matchs) {
                    if ($matchs["id_user"] == $session->get("id")) {
                        $pourcentage = $matchs["percent_match"];
                        foreach ($annonce["doc"]["matches"] as $matchs) {
                            if ($matchs["percent_match"] > $pourcentage) {
                                $classement++;
                            }
                        }
                    }
                    if (in_array($matchs["id_user"], $annonce["doc"]["postulants"]) == false) {
                        $invited[] = $matchs["id_user"];
                    }
                }
            }
        }
        return $this->render('offre.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'inviteds' => $invited,
            'id' => $id,
            'is_recruteur' => $session->get('is_recruteur'),
            'percent_match' => $pourcentage,
            'classement' => $classement,
            'is_premium' => $session->get("is_premium")
        ]);
    }

    public function postuler($id)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://3296c880.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $annonce_finale = null;
        $invited = [];

        $session = new Session();
        $session->start();

        $pourcentage = 0;
        $classement = 1;

        foreach ($contents["rows"] as $annonce) {
            if ($annonce["doc"]["_id"] == $id) {
                if (in_array($session->get('id'), $annonce["doc"]["postulants"]) == false)
                    array_push($annonce["doc"]["postulants"], $session->get('id'));
                $annonce_finale = $annonce;
                foreach ($annonce["doc"]["matches"] as $matchs) {
                    if ($matchs["id_user"] == $session->get("id")) {
                        $pourcentage = $matchs["percent_match"];
                        foreach ($annonce["doc"]["matches"] as $matchs) {
                            if ($matchs["percent_match"] > $pourcentage) {
                                $classement++;
                            }
                        }
                    }
                    if (in_array($matchs["id_user"], $annonce["doc"]["postulants"]) == false)
                        $invited[] = $matchs["id_user"];
                }
                $response_update = $client->request('PUT', 'https://3296c880.ngrok.io/annonces/' . $id, [
                    "headers" => [
                        "Content-Type" => "application/json"
                    ],
                    'body' => json_encode($annonce["doc"])
                ]);
                $contents_update = $response_update->toArray();

                $response = $client->request('GET', 'https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true');
                $contents = $response->toArray();
            }
        }

        return $this->render('offre.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'inviteds' => $invited,
            'id' => $id,
            'is_recruteur' => $session->get('is_recruteur'),
            'percent_match' => $pourcentage,
            'classement' => $classement,
            'is_premium' => $session->get("is_premium")
        ]);
    }

    public function accepter($id, $offre_id)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://3296c880.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $annonce_finale = null;
        $invited = [];

        $session = new Session();
        $session->start();

        $pourcentage = 0;
        $classement = 1;

        foreach ($contents["rows"] as $annonce) {
            if ($annonce["doc"]["_id"] == $offre_id) {
                if (in_array($id, $annonce["doc"]["postulants_acceptes"]) == false)
                    array_push($annonce["doc"]["postulants_acceptes"], $id);
                $annonce_finale = $annonce;
                foreach ($annonce["doc"]["matches"] as $matchs) {
                    if ($matchs["id_user"] == $session->get("id")) {
                        $pourcentage = $matchs["percent_match"];
                        foreach ($annonce["doc"]["matches"] as $matchs) {
                            if ($matchs["percent_match"] > $pourcentage) {
                                $classement++;
                            }
                        }
                    }
                    if (in_array($matchs["id_user"], $annonce["doc"]["postulants"]) == false)
                        $invited[] = $matchs["id_user"];
                }
                $response_update = $client->request('PUT', 'https://3296c880.ngrok.io/annonces/' . $offre_id, [
                    "headers" => [
                        "Content-Type" => "application/json"
                    ],
                    'body' => json_encode($annonce["doc"])
                ]);
                $contents_update = $response_update->toArray();

                $response = $client->request('GET', 'https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true');
                $contents = $response->toArray();


                $data = array(
                    "id_user_1" => $session->get("id"),
                    "id_user_2" => $id,
                    "offre_id" => $offre_id,
                    "messages" => []
                );

                $response_invite = $client->request("POST", "https://3296c880.ngrok.io/conversations", [
                    "headers" => [
                        "Content-Type" => "application/json"
                    ],
                    'body' => json_encode($data)
                ]);
                $contents_invite = $response_invite->toArray();



                $data = array(
                    'type' => "accepter",
                    'id_user' => $id,
                    'message' => "Votre demande de postulation à l'annonce '" . $annonce_finale["doc"]["title"] . "' a été acceptée.",
                    'id_offre' => $offre_id
                );

                $response_accepter_notif = $client->request("POST", "https://3296c880.ngrok.io/notifications", [
                    "headers" => [
                        "Content-Type" => "application/json"
                    ],
                    'body' => json_encode($data)
                ]);
                $contents_accepter_notif = $response_accepter_notif->toArray();
            }
        }

        return $this->render('offre.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'inviteds' => $invited,
            'id' => $offre_id,
            'is_recruteur' => $session->get('is_recruteur'),
            'percent_match' => $pourcentage,
            'classement' => $classement,
            'is_premium' => $session->get("is_premium")
        ]);
    }

    public function refuser($id, $offre_id)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://3296c880.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $annonce_finale = null;
        $invited = [];

        $session = new Session();
        $session->start();

        $pourcentage = 0;
        $classement = 1;

        foreach ($contents["rows"] as $annonce) {
            if ($annonce["doc"]["_id"] == $offre_id) {
                if (in_array($id, $annonce["doc"]["postulants_refuses"]) == false)
                    array_push($annonce["doc"]["postulants_refuses"], $id);
                $annonce_finale = $annonce;
                foreach ($annonce["doc"]["matches"] as $matchs) {
                    if ($matchs["id_user"] == $session->get("id")) {
                        $pourcentage = $matchs["percent_match"];
                        foreach ($annonce["doc"]["matches"] as $matchs) {
                            if ($matchs["percent_match"] > $pourcentage) {
                                $classement++;
                            }
                        }
                    }
                    if (in_array($matchs["id_user"], $annonce["doc"]["postulants"]) == false)
                        $invited[] = $matchs["id_user"];
                }
                $response_update = $client->request('PUT', 'https://3296c880.ngrok.io/annonces/' . $offre_id, [
                    "headers" => [
                        "Content-Type" => "application/json"
                    ],
                    'body' => json_encode($annonce["doc"])
                ]);
                $contents_update = $response_update->toArray();

                $response = $client->request('GET', 'https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true');
                $contents = $response->toArray();


                $data = array(
                    'type' => "refuser",
                    'id_user' => $id,
                    'message' => "Votre demande de postulation à l'annonce '" . $annonce_finale["doc"]["title"] . "' a été refusée.",
                    'id_offre' => $offre_id
                );

                $response_refuser_notif = $client->request("POST", "https://3296c880.ngrok.io/notifications", [
                    "headers" => [
                        "Content-Type" => "application/json"
                    ],
                    'body' => json_encode($data)
                ]);
                $contents_accepter_notif = $response_refuser_notif->toArray();
            }
        }

        return $this->render('offre.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'inviteds' => $invited,
            'id' => $offre_id,
            'is_recruteur' => $session->get('is_recruteur'),
            'percent_match' => $pourcentage,
            'classement' => $classement,
            'is_premium' => $session->get("is_premium")
        ]);
    }

    public function inviter($offre_id, $user_id)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://3296c880.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://3296c880.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $annonce_finale = null;
        $invited = [];

        $session = new Session();
        $session->start();

        $pourcentage = 0;
        $classement = 1;

        foreach ($contents["rows"] as $annonce) {
            if ($annonce["doc"]["_id"] == $offre_id) {
                $annonce_finale = $annonce;
                foreach ($annonce["doc"]["matches"] as $matchs) {
                    if ($matchs["id_user"] == $session->get("id")) {
                        $pourcentage = $matchs["percent_match"];
                        foreach ($annonce["doc"]["matches"] as $matchs) {
                            if ($matchs["percent_match"] > $pourcentage) {
                                $classement++;
                            }
                        }
                    }
                    if (in_array($matchs["id_user"], $annonce["doc"]["postulants"]) == false) {
                        $invited[] = $matchs["id_user"];
                    }
                }
            }
        }

        $data = array(
            'type' => "invitation",
            'id_user' => $user_id,
            'message' => "Vous avez été invité à postuler à l'annonce '" . $annonce_finale["doc"]["title"] . "'",
            'id_offre' => $offre_id
        );

        $response_invite = $client->request("POST", "https://3296c880.ngrok.io/notifications", [
            "headers" => [
                "Content-Type" => "application/json"
            ],
            'body' => json_encode($data)
        ]);
        $contents_invite = $response_invite->toArray();


        return $this->render('offre.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'inviteds' => $invited,
            'id' => $offre_id,
            'is_recruteur' => $session->get('is_recruteur'),
            'percent_match' => $pourcentage,
            'classement' => $classement,
            'is_premium' => $session->get("is_premium")
        ]);
    }
}
