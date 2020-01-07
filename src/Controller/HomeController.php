<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;

class HomeController extends AbstractController
{

    public function displayHome(Request $request)
    {
        $test = 1;
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://9350ba5d.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $contents = $response->toArray();


        for ($i = 0; $i < count($contents) - 1; $i++) {
            $tmp_user = $contents["rows"][$i]["doc"]["mail"];
            $tmp_password = $contents["rows"][$i]["doc"]["password"];
            if ($tmp_user == $username && $tmp_password == $password) {
                return $this->render('home.html.twig', array('test' => $test));
            }
        }
        return $this->render('login.html.twig');
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
