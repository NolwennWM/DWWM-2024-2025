<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    #[Route("/bonjour/anglais/{username}", name:'app_hello')]
    public function hello($username): Response
    {

        return $this->redirectToRoute("app_bonjour", ["nom"=>"Smith", "prenom"=>$username]);
    }
    #[Route("/bonjour/{nom}/{prenom}", name:'app_bonjour')]
    public function bonjour($nom, $prenom): Response
    {
        dump($nom, $prenom);
        // dd($nom, $prenom);
        return $this->render("home/bonjour.html.twig", [
            "nom"=>$nom,
            "prenom"=>$prenom
        ]);
    }
}
