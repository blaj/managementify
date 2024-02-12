<?php

namespace App\Dashboard\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route(path: '/dashboard', name: 'dashboard_')]
class DashboardController extends AbstractController {

  #[Route(path: '/', name: 'index', methods: ['GET'])]
  public function index(): Response {
    return $this->render('dashboard/index.html.twig');
  }
}