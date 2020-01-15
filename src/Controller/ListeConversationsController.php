<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\Session;

class ListeConversationsController extends AbstractController
{

    public function displayListeConversations()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://3296c880.ngrok.io/conversations/_all_docs?include_docs=true');
        $convs = $response->toArray();
        $resp_user = $client->request('GET', 'https://3296c880.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $resp_user->toArray();
        $session = new Session();
        $session->start();
        $id_user = $session->get("id");
        $conversations = [];

        foreach ($convs["rows"] as $conv)
        {
            if ($id_user == $conv["doc"]["id_user_1"] or $id_user == $conv["doc"]["id_user_2"])
                $conversations[] = $conv;
        }

        return $this->render('liste-conversations.html.twig', [
            'conversations' => $conversations,
            'users' => $users,
            'id_user' => $id_user,
            'is_recruteur' => $session->get("is_recruteur")
        ]);
    }
}
