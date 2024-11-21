<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, CsrfTokenManagerInterface $tokenManager): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();


        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf' => $tokenManager->getToken('authenticate')->getValue(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/init', name: 'init', methods: ['GET'])]
    public function init(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response {
        $user = new Utilisateur();
        $user->setUsername("gior");
        
        $plaintextPassword = "gior";
        $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);

        $user->setPassword($hashedPassword);
        
        $em->persist($user);
        $em->flush();

        return $this->render('security/login.html.twig', [
            'error' => ''
        ]);
    }
}
