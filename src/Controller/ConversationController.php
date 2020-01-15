<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class ConversationController extends AbstractController
{

    public function displayConversation($id)
    {
        $session = new Session();
        $session->start();

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://3296c880.ngrok.io/conversations/_all_docs?include_docs=true');
        $convs = $response->toArray();
        $resp_user = $client->request('GET', 'https://3296c880.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $resp_user->toArray();
        $messages = null;
        $other = null;
        $user = null;
        $id_other = null;
        $id_user = $session->get('id');


        foreach ($convs["rows"] as $conv) {
            if ($conv["doc"]["_id"] == $id) {
                if ($id_user == $conv["doc"]["id_user_1"])
                    $id_other = $conv["doc"]["id_user_2"];
                else
                    $id_other = $conv["doc"]["id_user_1"];
                $messages = $conv["doc"]["messages"];
            }
        }

        foreach ($users["rows"] as $actual) {
            if ($actual["doc"]["_id"] == $id_user)
                $user = $actual;

            if ($actual["doc"]["_id"] == $id_other)
                $other = $actual;
        }



        return $this->render('conversation.html.twig', [
            'user' => $user,
            'messages' => $messages,
            'other' => $other,
            'id' => $id,
            'is_recruteur' => $session->get("is_recruteur")
        ]);
    }

    public function updateConversation($id, Request $request)
    {
        $session = new Session();
        $session->start();

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://3296c880.ngrok.io/conversations/_all_docs?include_docs=true');
        $convs = $response->toArray();
        $resp_user = $client->request('GET', 'https://3296c880.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $resp_user->toArray();
        $messages = null;
        $other = null;
        $user = null;
        $id_other = null;
        $id_user = $session->get('id');
        $new_message = $request->query->get('_message');
        foreach ($convs["rows"] as $conv) {
            if ($conv["doc"]["_id"] == $id) {
                array_push($conv["doc"]["messages"], (object) ['id_auteur' => $id_user, 'message' => $new_message]);
                $response_update = $client->request('PUT', 'https://3296c880.ngrok.io/conversations/' . $id, [
                    "headers" => [
                        "Content-Type" => "application/json"
                    ],
                    'body' => json_encode($conv["doc"])
                ]);
                $test = $response_update->toArray();
                $response = $client->request('GET', 'https://3296c880.ngrok.io/conversations/_all_docs?include_docs=true');
                $convs = $response->toArray();
            }
        }



        foreach ($convs["rows"] as $conv) {
            if ($conv["doc"]["_id"] == $id) {
                if ($id_user == $conv["doc"]["id_user_1"])
                    $id_other = $conv["doc"]["id_user_2"];
                else
                    $id_other = $conv["doc"]["id_user_1"];
                $messages = $conv["doc"]["messages"];
            }
        }

        foreach ($users["rows"] as $actual) {
            if ($actual["doc"]["_id"] == $id_user)
                $user = $actual;

            if ($actual["doc"]["_id"] == $id_other)
                $other = $actual;
        }



        return $this->render('conversation.html.twig', [
            'user' => $user,
            'messages' => $messages,
            'other' => $other,
            'id' => $id,
            'is_recruteur' => $session->get("is_recruteur")
        ]);
    }
}
