<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class RechercheController extends AbstractController
{

    public function displayRecherche()
    {
        $session = new Session();
        $session->start();
        $tmp = 0;

        return $this->render('recherche.html.twig', [
            'annonces' => [],
            'users' => [],
            'entreprises' => [],
            'tmp' => $tmp,
            "is_recruteur" => $session->get('is_recruteur')
        ]);
    }

    public function search(Request $request)
    {
        $session = new Session();
        $session->start();


        $client = HttpClient::create();
        $response = $client->request('GET', 'https://20678575.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://20678575.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $respentreprise = $client->request('GET', 'https://20678575.ngrok.io/entreprises/_all_docs?include_docs=true');
        $entreprises = $respentreprise->toArray();
        $words = explode(" ", $request->request->get('_search'));
        $tmp = 1;
        $entreprise_found = [];
        $annonce_found = [];
        $user_found = [];

        foreach ($words as $word) 
        {
            foreach ($entreprises["rows"] as $entreprise) 
            {
                $count = 0;
                $desc = explode(" ", $entreprise["doc"]["description"]);
                $names = explode(" ", $entreprise["doc"]["name"]);
                
                foreach ($names as $name) {
                    if (strtolower($word) == strtolower($name) and $count == 0 and in_array($entreprise, $entreprise_found) == false)
                    {   
                        $count++;
                        $entreprise_found[] = $entreprise;
                    }
                }
                if ($count == 0)
                {
                    foreach ($desc as $text) {
                        if (strtolower($word) == strtolower($text) and $count == 0 and in_array($entreprise, $entreprise_found) == false)
                        {
                            $count++;
                            $entreprise_found[] = $entreprise;
                        }
                    }
                }
            }

            foreach ($contents["rows"] as $annonce) 
            {
                $count = 0;
                $desc = explode(" ", $annonce["doc"]["description"]);
                $titles = explode(" ", $annonce["doc"]["title"]);
                
                foreach ($titles as $text)
                {
                    if (strtolower($word) == strtolower($text) and $count == 0 and in_array($annonce, $annonce_found) == false)
                    {   
                        $count++;
                        $annonce_found[] = $annonce;
                    }
                }

                if ($count == 0)
                {
                    foreach ($annonce["doc"]["competences"] as $competence)
                    {
                        if ($count == 0)
                        {
                            $names = explode(" ", $competence);

                            foreach ($names as $text)
                            {
                                if (strtolower($word) == strtolower($text) and $count == 0 and in_array($annonce, $annonce_found) == false)
                                {   
                                   $count++;
                                   $annonce_found[] = $annonce;
                                }
                            }
                        }
                    }
                }

                if ($count == 0)
                {
                    foreach ($entreprises["rows"] as $entreprise)
                    {
                        if ($entreprise["doc"]["_id"] == $annonce["doc"]["entreprise_id"] and $count == 0)
                        {
                            $names = explode(" ", $entreprise["doc"]["name"]);
                            foreach ($names as $text)
                            {
                                if (strtolower($word) == strtolower($text) and $count == 0 and in_array($annonce, $annonce_found) == false)
                                {   
                                   $count++;
                                   $annonce_found[] = $annonce;
                                }
                            }
                        }
                    }
                }

                if ($count == 0)
                {
                    foreach ($users["rows"] as $user)
                    {
                        if ($user["doc"]["_id"] == $annonce["doc"]["recruteur_id"] and $count == 0)
                        {
                            $names = explode(" ", $user["doc"]["name"]);
                            foreach ($names as $text)
                            {
                                if (strtolower($word) == strtolower($text) and $count == 0 and in_array($annonce, $annonce_found) == false)
                                {   
                                   $count++;
                                   $annonce_found[] = $annonce;
                                }
                            }
                        }
                        if ($user["doc"]["_id"] == $annonce["doc"]["recruteur_id"] and $count == 0)
                        {
                            $lastnames = explode(" ", $user["doc"]["last_name"]);
                            foreach ($lastnames as $text)
                            {
                                if (strtolower($word) == strtolower($text) and $count == 0 and in_array($annonce, $annonce_found) == false)
                                {   
                                   $count++;
                                   $annonce_found[] = $annonce;
                                }
                            }
                        }
                    }
                }

                if ($count == 0)
                {    
                    foreach ($desc as $text) {
                        if (strtolower($word) == strtolower($text) and $count == 0 and in_array($annonce, $annonce_found) == false)
                        {
                            $count++;
                            $annonce_found[] = $annonce;
                        }
                    }
                }
                
            }

            foreach ($users["rows"] as $user) 
            {
                $count = 0;
                $desc = explode(" ", $user["doc"]["description"]);
                $names = explode(" ", $user["doc"]["name"]);
                $lastnames = explode(" ", $user["doc"]["last_name"]);
                
                
                foreach ($names as $text)
                {
                    if (strtolower($word) == strtolower($text) and $count == 0 and in_array($user, $user_found) == false)
                    {   
                        $count++;
                        $user_found[] = $user;
                    }
                }
                
                foreach ($lastnames as $text)
                {
                    if (strtolower($word) == strtolower($text) and $count == 0 and in_array($user, $user_found) == false)
                    {   
                        $count++;
                        $user_found[] = $user;
                    }
                }

                if ($count == 0)
                {
                    foreach ($user["doc"]["competences"] as $competence)
                    {
                        if ($count == 0)
                        {
                            $comps = explode(" ", $competence);
                            foreach ($comps as $text)
                            {
                                if (strtolower($word) == strtolower($text) and $count == 0 and in_array($user, $user_found) == false)
                                {   
                                   $count++;
                                   $user_found[] = $user;
                                }
                            }
                        }   
                    }
                }
                    
                if ($count == 0)
                {
                    foreach ($user["doc"]["experiences"] as $experience)
                    {
                        if ($count == 0)
                        {
                            $titles = explode(" ", $experience["title"]); 
                            
                            foreach ($titles as $text)
                            {
                                if (strtolower($word) == strtolower($text) and $count == 0 and in_array($user, $user_found) == false)
                                {   
                                    $count++;
                                    $user_found[] = $user;
                                }
                            }
                        }
                        if ($count == 0)
                        {
                            $desc2 = explode(" ", $experience["description"]);
                            foreach ($desc2 as $text) {
                                if (strtolower($word) == strtolower($text) and $count == 0 and in_array($user, $user_found) == false)
                                {
                                    $count++;
                                    $user_found[] = $user;
                                }
                            }
                        }
                        if ($count == 0)
                        {
                            $ents = explode(" ", $experience["entreprise"]);
                            foreach ($ents as $text) {
                                if (strtolower($word) == strtolower($text) and $count == 0 and in_array($user, $user_found) == false)
                                {
                                    $count++;
                                    $user_found[] = $user;
                                }
                            }
                        }
                    }          
                }
                
                if ($count == 0)
                {
                    foreach ($user["doc"]["formations"] as $formation)
                    {
                        if ($count == 0)
                        {
                            $titles = explode(" ", $formation["title"]); 
                            
                            foreach ($titles as $text)
                            {
                                if (strtolower($word) == strtolower($text) and $count == 0 and in_array($user, $user_found) == false)
                                {   
                                    $count++;
                                    $user_found[] = $user;
                                }
                            }
                        }
                        if ($count == 0)
                        {
                            $desc2 = explode(" ", $formation["description"]);
                            foreach ($desc2 as $text) {
                                if (strtolower($word) == strtolower($text) and $count == 0 and in_array($user, $user_found) == false)
                                {
                                    $count++;
                                    $user_found[] = $user;
                                }
                            }
                        }
                        if ($count == 0)
                        {
                            $ecoles = explode(" ", $formation["ecole"]);
                            foreach ($ecoles as $text) {
                                if (strtolower($word) == strtolower($text) and $count == 0 and in_array($user, $user_found) == false)
                                {
                                    $count++;
                                    $user_found[] = $user;
                                }
                            }
                        }
                    }          
                }

                if ($count == 0)
                {    
                    foreach ($desc as $text) {
                        if (strtolower($word) == strtolower($text) and $count == 0 and in_array($user, $user_found) == false)
                        {
                            $count++;
                            $user_found[] = $user;
                        }
                    }
                }
            }
        }
        return $this->render('recherche.html.twig', [
            'annonces' => $annonce_found,
            'users' => $user_found,
            'entreprises' => $entreprise_found,
            'tmp' => $tmp,
            "is_recruteur" => $session->get('is_recruteur')
        ]);
    }
}
