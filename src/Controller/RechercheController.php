<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;

class RechercheController extends AbstractController
{

    public function displayRecherche()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://56035fdf.ngrok.io/annonces/_all_docs?include_docs=true');
        $annonces_rows = $response->toArray();
        $contents = $annonces_rows["rows"];
        $respuser = $client->request('GET', 'https://56035fdf.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users_rows = $respuser->toArray();
        $users = $users_rows["rows"];
        $respentreprise = $client->request('GET', 'https://56035fdf.ngrok.io/entreprises/_all_docs?include_docs=true');
        $entreprises_rows = $respentreprise->toArray();
        $entreprises = $entreprises_rows["rows"];
        return $this->render('recherche.html.twig', [
            'annonces' => $contents,
            'users' => $users,
            'entreprises' => $entreprises
        ]);
    }

    public function search(Request $request)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://56035fdf.ngrok.io/annonces/_all_docs?include_docs=true');
        $contents = $response->toArray();
        $respuser = $client->request('GET', 'https://56035fdf.ngrok.io/utilisateurs/_all_docs?include_docs=true');
        $users = $respuser->toArray();
        $respentreprise = $client->request('GET', 'https://56035fdf.ngrok.io/entreprises/_all_docs?include_docs=true');
        $entreprises = $respentreprise->toArray();
        $words = explode(" ", $request->request->get('_search'));
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
                    if (strtolower($word) == strtolower($name))
                    {   
                        $count++;
                        $entreprise_found[] = $entreprise;
                    }
                }
                if ($count == 0)
                {
                    foreach ($desc as $text) {
                        if (strtolower($word) == strtolower($text) and $count == 0)
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
                    if (strtolower($word) == strtolower($text) and $count == 0)
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
                                if (strtolower($word) == strtolower($text) and $count == 0)
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
                        if (strtolower($word) == strtolower($text) and $count == 0)
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
                    if (strtolower($word) == strtolower($text) and $count == 0)
                    {   
                        $count++;
                        $user_found[] = $user;
                    }
                }
                
                foreach ($lastnames as $text)
                {
                    if (strtolower($word) == strtolower($text) and $count == 0)
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
                                if (strtolower($word) == strtolower($text) and $count == 0)
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
                                if (strtolower($word) == strtolower($text) and $count == 0)
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
                                if (strtolower($word) == strtolower($text) and $count == 0)
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
                                if (strtolower($word) == strtolower($text) and $count == 0)
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
                                if (strtolower($word) == strtolower($text) and $count == 0)
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
                        if (strtolower($word) == strtolower($text) and $count == 0)
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
            'entreprises' => $entreprise_found
        ]);
    }
}
