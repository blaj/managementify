<?php

namespace App\Visit\Controller;

use App\Security\Dto\UserData;
use App\Visit\Dto\VisitCellDataRequest;
use App\Visit\Form\Type\VisitCreateType;
use App\Visit\Service\VisitService;
use App\Visit\ValueResolver\VisitCellDataRequestValueResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/visit/modal', name: 'visit_modal_')]
class VisitModalController extends AbstractController {

  public function __construct(
      private readonly VisitService $visitService,
      private readonly RequestStack $requestStack) {}

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(
      #[MapQueryString(resolver: VisitCellDataRequestValueResolver::class)] VisitCellDataRequest $visitCellDataRequest,
      UserData $userData,
      Request $request): Response {
    $form =
        $this->createForm(
            VisitCreateType::class,
            $visitCreateRequest = $this->visitService->getCreateRequest($visitCellDataRequest),
            [
                'companyId' => $userData->getCompanyId(),
                'action' => $this->generateUrl('visit_modal_create')]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->visitService->create($visitCreateRequest, $userData->getCompanyId());

      $this->requestStack->getSession()->remove(VisitCellDataRequestValueResolver::$sessionName);

      return $this->redirectToRoute('visit_index');
    }

    return $this->render('visit/modal/create/create.html.twig', ['form' => $form]);
  }
}