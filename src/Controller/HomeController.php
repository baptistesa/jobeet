<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\Session;


class HomeController extends AbstractController
{
    public function displayHome(Request $request)
    {
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://ffb7c3a5.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $contents = $response->toArray();


        for ($i = 0; $i < count($contents["rows"]); $i++) {
            $tmp_user = $contents["rows"][$i]["doc"]["mail"];
            $tmp_password = $contents["rows"][$i]["doc"]["password"];
            if ($tmp_user == $username && $tmp_password == $password) {
                $session = new Session();
                $session->start();
                $session->set('mail', $username);
                $session->set('name', $contents["rows"][$i]["doc"]["name"]);
                $session->set('last_name', $contents["rows"][$i]["doc"]["last_name"]);
                $session->set('description', $contents["rows"][$i]["doc"]["description"]);
                $session->set('is_recruteur', $contents["rows"][$i]["doc"]["is_recruteur"]);
                $session->set('entreprise_id', $contents["rows"][$i]["doc"]["entreprise_id"]);
                $session->set('id', $contents["rows"][$i]["doc"]["_id"]);
                $is_recruteur = false;
                if ($contents["rows"][$i]["doc"]["is_recruteur"])
                    $is_recruteur = true;
                return $this->render('home.html.twig', array('is_recruteur' => $is_recruteur));
            }
        }
        return $this->render('login.html.twig');
    }

    public function inscription(Request $request)
    {
        $prenom = $request->request->get('_prenom');
        $nom = $request->request->get('_nom');
        $mail = $request->request->get('_mail');
        $password = $request->request->get('_password');
        $description = $request->request->get('_description');

        $data = array(
            'name' => $prenom,
            'last_name' => $nom,
            'mail' => $mail,
            'password' => $password,
            'description' => $description
        );

        $client = HttpClient::create();
        $response = $client->request('POST', 'https://ffb7c3a5.ngrok.io/utilisateurs', [
            "headers" => [
                "Content-Type" => "application/json"
            ],
            'body' => json_encode($data)
        ]);
        $contents = $response->toArray();
        $session = new Session();
        $session->start();
        $session->set('id', $contents["id"]);
        $session->set('mail', $mail);
        $session->set('name', $prenom);
        $session->set('last_name', $nom);
        $session->set('description', $description);
        $session->set('is_recruteur', false);

        return $this->render('home.html.twig', array('is_recruteur' => false));
    }

    public function accueil(Request $request)
    {
        $session = new Session();
        $session->start();
        return $this->render('home.html.twig', array("is_recruteur" => $session->get('is_recruteur')));
    }

    public function goToLogin()
    {
        return $this->redirectToRoute('login');
    }

    public function goToRegister()
    {
        return $this->redirectToRoute('login');
    }

    public function goToProfilentreprise()
    {
        return $this->redirectToRoute('profil-entreprise');
    }

    public function goToListentreprise()
    {
        return $this->redirectToRoute('liste-entreprise');
    }
}
