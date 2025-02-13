<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/dashboard_company', name: 'dashboard_company')]
    public function dashboardCompany(): Response
    {
        return $this->render('home/dashboard_company.html.twig');
    }

    #[Route('/dashboard_candidate', name: 'dashboard_candidate')]
    public function dashboardCandidate(): Response
    {
        return $this->render('home/dashboard_candidate.html.twig');
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
