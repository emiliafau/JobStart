<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController 
{
    #[Route('/', name:'home')]
    public function index ():Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('home/login.html.twig');
    }

    #[Route('/login_process', name: 'login_process')]
    public function loginProcess(): Response
    {
        return $this->render('home/login.html.twig');
    }

    #[Route('/register', name: 'register')]
    public function register(): Response
    {
        return $this->render('home/register.html.twig');
    }

    #[Route('/register_process', name: 'register_process')]
    public function registerProcess(): Response
    {
        return $this->render('home/register_process.html.twig');
    }
    
    #[Route('/privacy-policy', name: 'privacy-policy')]

    public function privacyPolicy(): Response
    {
    return new Response('En construction');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
    return new Response('En construction');
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
    return new Response('En construction');
    }

    #[Route('/terms-of-use', name: 'terms-of-use')]
    public function termsOfUse(): Response
    {
    return new Response('En construction');
    }

    #[Route('/legal-notice', name: 'legal-notice')]
    public function legalNotice(): Response
    {
    return new Response('En construction');
    }

}
  

