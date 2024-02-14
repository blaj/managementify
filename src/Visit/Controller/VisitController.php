<?php

namespace App\Visit\Controller;

use App\Visit\Dto\VisitFilterRequest;
use App\Visit\Form\Type\VisitFilterType;
use App\Visit\Service\VisitCalendarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route(path: '/visit', name: 'visit_')]
class VisitController extends AbstractController {

  public function __construct(
      private readonly VisitCalendarService $visitCalendarService) {}

  #[Route(path: '/', name: 'index', methods: ['GET'])]
  public function index(Request $request): Response {
    $form =
        $this->createForm(VisitFilterType::class, $visitFilterRequest = new VisitFilterRequest());
    $form->handleRequest($request);

    return $this->render(
        'visit/index/index.html.twig',
        [
            'form' => $form,
            'calendarDto' => $this->visitCalendarService->getCalendar($visitFilterRequest)]);
  }
}