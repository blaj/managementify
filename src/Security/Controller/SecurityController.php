<?php

namespace App\Security\Controller;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/', name: 'security_')]
class SecurityController extends AbstractController {

  #[IsGranted(new Expression("!is_authenticated()"))]
  #[Route(path: '/login', name: 'login', methods: ['GET', 'POST'])]
  public function login(AuthenticationUtils $authenticationUtils): Response {
    return $this->render('security/login.html.twig',
        [
            'lastUsername' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()]);
  }

  #[IsGranted(new Expression("is_authenticated()"))]
  #[Route(path: '/logout', name: 'logout', methods: ['GET', 'POST'])]
  public function logout(): void {
    throw new RuntimeException('Action is not allowed!');
  }
}