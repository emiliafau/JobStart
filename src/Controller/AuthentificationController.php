<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use App\Entity\Entreprise;


final class AuthentificationController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        // Retourne simplement le formulaire de connexion
        return $this->render('authentification/login.html.twig');
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


    #[Route('/register', name: 'register')]
    public function register(): Response
    {
        // Affiche le formulaire d'enregistrement
        return $this->render('authentification/register.html.twig');
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

    // La logique de déconnexion est gérée automatiquement par Symfony, donc pas besoin de méthode ici
    public function logout()
    {
        // Symfony gère le logout automatiquement
    }

    #[Route('/password_forgot', name: 'password_forgot')]
    public function passwordForgot(): Response
    {
        // Retourne simplement le formulaire de connexion
        return $this->render('authentification/password_forgot.html.twig');
    }

    #[Route('/password_forgot_process', name: 'password_forgot_process')]
    public function passwordForgotProcess(Request $request, EntityManagerInterface $entityManager , MailerInterface $mailer): Response
    {
        $email = $request->request->get('recovery-mail');

        if (!$email){
            $this->addFlash('error', 'Veuillez entrer une adresse mail valide');
            return $this->redirectToRoute('password_forgot');
        }
    
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['mail'=>$email]);
    

    if (!$user){
        $this->addFlash('error' , 'Aucun compte trouvé avec cette adresse mail');
        return $this->redirectToRoute('password_forgot');
    }

    $token = bin2hex(random_bytes(32));

    $request->getSession()->set('password_reset_token', $token);
    $request->getSession()->set('password_reset_email', $email);

    $resetLink = $this->generateUrl('password_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

    $emailMessage = (new Email())
        ->from('emiliafau.dev@gmail.com')
        ->to($email)
        ->subject('Réinitialisation de votre mot de passe')
        ->html("<p>Bonjour</p>
                test via brevo");

    $mailer->send($emailMessage);

    $this->addFlash('success', 'Un email de réinitialisation a bien été envoyé .');

    return $this->redirectToRoute('login');
    }

    #[Route('/password_reset/{token}', name: 'password_reset')]
    public function passwordReset(Request $request, string $token, EntityManagerInterface $entityManager , UserPasswordHasherInterface $passwordHasher): Response
    {
    $session = $request->getSession();
    $email = $session->get('password_reset_email');
    $savedToken = $session->get('password_reset_token');

    if (!$email || $token !== $savedToken) {
        $this->addFlash('error', 'Lien invalide ou expiré.');
        return $this->redirectToRoute('password_forgot');
    }

    if ($request->isMethod('POST')) {
        $newPassword = $request->request->get('new_password');

        // Vérifier si le mot de passe est valide
        if (strlen($newPassword) < 6) {
            $this->addFlash('error', 'Le mot de passe doit contenir au moins 6 caractères.');
            return $this->redirectToRoute('password_reset', ['token' => $token]);
        }

        // Récupérer l'utilisateur
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['mail' => $email]);

        if ($user) {
            // Hacher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setMotDePasse($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été mis à jour.');
            return $this->redirectToRoute('login');
        }
    }

    return $this->render('authentification/password_reset.html.twig', ['token' => $token]);
  }
}