<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;

class OffreController extends AbstractController
{

    public function displayOffre($id) {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://d10080de.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://d10080de.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $annonce_finale = null;
        $invited = [];
        foreach ($contents["rows"] as $annonce)
        {
            if ($annonce["doc"]["_id"] == $id)
            {
                $annonce_finale = $annonce;
                foreach ($annonce["doc"]["matches"] as $matchs)
                {
                    if (in_array($matchs["id_user"], $annonce["doc"]["postulants"]) == false)
                        $invited[] = $matchs["id_user"];
                }
            }
        }
        return $this->render('offre.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'inviteds' => $invited,
            'id' => $id
        ]);
    }
}