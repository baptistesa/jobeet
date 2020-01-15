<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\Session;

class ProfilCandidatController extends AbstractController
{

    public function displayProfilCandidat($id)
    {
        $session = new Session();
        $session->start();

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://3296c880.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $res = "0";
        foreach ($contents["rows"] as $user)
        {
            if ($user["doc"]["_id"] == $id)
                $res = $user;
        }
        return $this->render('profil-candidat.html.twig', [
            "id" => $id,
            "utilisateur" => $res,
            "is_recruteur" => $session->get('is_recruteur')
        ]);
    }
}
