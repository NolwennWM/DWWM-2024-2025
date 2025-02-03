<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

// #[Route("/user")]
final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            "fruits"=>["banane", "tomate", "ananas"],
            "pays"=>["france"=>"Bonjour le monde !", "angleterre"=>"Hello World"],
            "chiffre"=> rand(0,10),
            "vide"=>[],
            "xss"=>"<script>alert('coucou');</script>"
        ]);
    }
    #[Route("/bonjour/anglais/{username<^[a-zA-Z]+$>?John}", name:'app_hello')]
    public function hello($username): Response
    {
        $this->addFlash("bonjour", "Hello $username");
        $this->addFlash("redirection", "Vous avez été redirigé !");


        return $this->redirectToRoute("app_bonjour", ["nom"=>"Smith", "prenom"=>$username]);
    }
    /* 
        /bonjour/dupont --> /bonjour/dupont/jean
    */
    #[Route("/bonjour/{nom}/{prenom}", name:'app_bonjour', defaults:["prenom"=>"Jean"], requirements:["prenom"=>"^[a-zA-Z]+$", "nom"=>"^[a-zA-Z]+$"])]
    public function bonjour($nom, $prenom, Request $request): Response
    {
        dump($request);
        // dd($nom, $prenom);
        // Compteur de visite sur la page
        $sess = $request->getSession();
        if($sess->has("nbVisite"))
        {
            $nbVisite = $sess->get("nbVisite")+1;
        }else
        {
            $nbVisite = 1;
        }
        $sess->set("nbVisite", $nbVisite);
        // message flash
        $this->addFlash("bonjour", "Bonjour $prenom $nom");

        return $this->render("home/bonjour.html.twig", [
            "nom"=>$nom,
            "prenom"=>$prenom
        ]);
    }
}
