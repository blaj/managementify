<?php

namespace App\Home\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/', name: 'home_')]
class HomeController extends AbstractController {

  #[Route(path: '/', name: 'index', methods: ['GET'])]
  public function index(): Response {
    return $this->redirectToRoute('dashboard_index');
  }
}
