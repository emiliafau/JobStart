<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\utilisateur;


class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('home/login.html.twig');
    }

    #[Route('/login_process', name: 'login_process')]
    public function loginProcess(Request $request, EntityManagerInterface $entityManager): Response
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $user = $entityManager->getRepository(utilisateur::class)->findOneBy(['mail' => $username]);
        if ($user && $user->getMotDePasse() === $password) {

        if ($user->getRole() === 'entreprise') {
                return $this->redirectToRoute('dashboard_company');
        } else {
                return $this->redirectToRoute('dashboard_candidate');
        }
        }
        $this->addFlash('error', 'Nom d\'Utilisateur ou Mot de Passe incorrecte');
        return $this->redirectToRoute('login');
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
