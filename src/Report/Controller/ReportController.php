<?php

namespace App\Report\Controller;

use App\Report\Dto\VisitSummary\Request\VisitSummaryGenerateRequest;
use App\Report\Form\Type\VisitSummaryGenerateType;
use App\Report\Service\ReportService;
use App\Report\Service\VisitSummaryReportService;
use App\Security\Dto\UserData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/report', name: 'report_')]
class ReportController extends AbstractController {

  public function __construct(
      private readonly ReportService $reportService,
      private readonly VisitSummaryReportService $visitSummaryReportService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(): Response {
    return $this->render(
        'report/list/list.html.twig',
        ['reportsList' => $this->reportService->getList()]);
  }

  #[Route(path: '/generate/visit-summary', name: 'generate_visit_summary', methods: ['GET'])]
  public function generateVisitSummary(UserData $userData, Request $request): Response {
    $form =
        $this->createForm(
            VisitSummaryGenerateType::class,
            $visitSummaryGenerateRequest = new VisitSummaryGenerateRequest(),
            ['action' => $this->generateUrl('report_generate_visit_summary')]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      return $this->file(
          $this->visitSummaryReportService->generate(
              $visitSummaryGenerateRequest,
              $userData->getUserId(),
              $userData->getCompanyId()));
    }

    return $this->render('report/modal/visit-summary.html.twig', ['form' => $form]);
  }
}