<?php

namespace App\Company\Controller;

use App\Common\Const\FlashMessageConst;
use App\Company\Form\Type\CompanyUpdateType;
use App\Company\Service\CompanyService;
use App\Security\Dto\UserData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/company', name: 'company_')]
class CompanyController extends AbstractController {

  public function __construct(private readonly CompanyService $companyService) {}

  #[IsGranted('ROLE_COMPANY_DETAILS')]
  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $id, UserData $userData): Response {
    if ($id !== $userData->getCompanyId()) {
      throw new BadRequestHttpException();
    }

    $companyDetailsDto = $this->companyService->getDetails($id);

    if ($companyDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'company/details/details.html.twig',
        ['companyDetailsDto' => $companyDetailsDto]);
  }

  #[IsGranted('ROLE_COMPANY_UPDATE')]
  #[Route(path: '/{id}/update', name: 'update', requirements: ['id' => '\d+'], methods: [
      'GET',
      'PUT'])]
  public function update(int $id, UserData $userData, Request $request): Response {
    if ($id !== $userData->getCompanyId()) {
      throw new BadRequestHttpException();
    }

    $companyUpdateRequest = $this->companyService->getUpdateRequest($id);

    if ($companyUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form = $this->createForm(CompanyUpdateType::class, $companyUpdateRequest, ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->companyService->update($id, $companyUpdateRequest);

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('company-edited-successfully'));

      return $this->redirectToRoute('company_details', ['id' => $id]);
    }

    return $this->render('company/update/update.html.twig', ['form' => $form, 'id' => $id]);
  }
}