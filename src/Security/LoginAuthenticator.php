<?php
namespace App\Security;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginAuthenticator extends AbstractAuthenticator
{
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('username');

        if (!$email) {
            throw new CustomUserMessageAuthenticationException('Email non fourni.');
        }

        $user = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['mail' => $email]);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Utilisateur non trouvé.');
        }

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password'))
        );
    }

    public function onAuthenticationSuccess(Request $request, \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token, string $firewallName): ?RedirectResponse
    {
        $user = $token->getUser();
        
        // Redirige vers le dashboard approprié en fonction du rôle
        if ($user->getRoles() === Utilisateur::ROLE_ENTREPRISE) {
            return new RedirectResponse($this->router->generate('dashboard_company'));
        } elseif ($user->getRoles() === Utilisateur::ROLE_CANDIDAT) {
            return new RedirectResponse($this->router->generate('dashboard_candidate'));
        }

        return new RedirectResponse($this->router->generate('home'));
    }

    public function onAuthenticationFailure(Request $request, \Symfony\Component\Security\Core\Exception\AuthenticationException $exception): ?RedirectResponse
    {
        return new RedirectResponse($this->router->generate('login'));
    }

    public function getLoginUrl(Request $request): string
    {
        return $this->router->generate('login');
    }
}



