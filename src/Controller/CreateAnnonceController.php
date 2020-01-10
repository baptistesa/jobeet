<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\Session;


class CreateAnnonceController extends AbstractController
{
    public function displayForm(Request $request) {
        return $this->render('create-annonce.html.twig');
    }

    public function postForm(Request $request)
    {
        $test = 1;
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
        $response = $client->request('POST', 'https://56035fdf.ngrok.io/utilisateurs', [
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
        
        return $this->render('home.html.twig', array('test' => $test));
    }
}
