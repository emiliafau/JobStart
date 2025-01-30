<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use App\Entity\Entreprise;

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

        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['mail' => $username]);

        if ($user && password_verify($password, $user->getMotDePasse())) {

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
        return $this->render('home/register.html.twig', [
            'role' => 'candidat'
        ]);
    }

    #[Route('/register_process', name: 'register_process', methods: ['POST'])]
    public function registerProcess(Request $request, EntityManagerInterface $entityManager): Response
    {   

        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $mail = $request->request->get('mail');
        $password = $request->request->get('password');
        $role = $request->request->get('role');
        $Siret = $request->request->get('siret');

        if ($role === Utilisateur::ROLE_CANDIDAT) {
            if (empty($nom) || empty($prenom) || empty($mail)) {
                $this->addFlash('error', 'Nom et prénom et mot de passe sont obligatoires pour un candidat');
                return $this->redirectToRoute('register');
            }
        } elseif ($role === Utilisateur::ROLE_ENTREPRISE) {
            if (empty($Siret)) {
                $this->addFlash('error', 'Le numéro de siret est obligatoire pour une entreprise');
                return $this->redirectToRoute('register');
            }
        }

        if (empty($mail) || empty($password) || empty($role)) {
            $this->addFlash('error', 'Veuillez remplir les champs');
            return $this->redirectToRoute('register');
        }

        $pattern = '/^(?=.*[0-9])(?=.*[!@#$%^&*(),.?":{}|<>]).{6,}$/';
        if (!preg_match($pattern, $password)) {
            $this->addFlash('error', 'Le mot de passe doit contenir au moins un chiffre, un caractère spécial et avoir 6 lettres minimum.');
            return $this->redirectToRoute('register');
        }

        $userexist = $entityManager->getRepository(Utilisateur::class)->findOneBy(['mail' => $mail]);

        if ($userexist) {
            $this->addFlash('error', 'Tous les champs sont utilisés');
            return $this->redirectToRoute('register');
        }

        $user = new Utilisateur();
        $user->setMail($mail);
        $user->setMotDePasse(password_hash($password, PASSWORD_BCRYPT));
        $user->setRole($role);
        $user->setCreatedAt(new \DateTime());


        if ($role === Utilisateur::ROLE_CANDIDAT) {
            $user->setNom($nom);
            $user->setPrenom($prenom);
        } elseif ($role == Utilisateur::ROLE_ENTREPRISE) {

            $entreprise = new Entreprise();
            $entreprise->setSiret($Siret);
            $entreprise->setUtilisateur($user);
            $user->setEntreprise($entreprise);
            $entityManager->persist($entreprise);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter');
        return $this->redirectToRoute('login');
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
