<?php

namespace App\Visit\Controller;

use App\Common\Const\FlashMessageConst;
use App\Security\Dto\UserData;
use App\Visit\Dto\VisitTypeCreateRequest;
use App\Visit\Form\Type\VisitTypeCreateType;
use App\Visit\Form\Type\VisitTypeUpdateType;
use App\Visit\Service\VisitTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/visit/type', name: 'visit_type_')]
class VisitTypeController extends AbstractController {

  public function __construct(private readonly VisitTypeService $visitTypeService) {}

  #[IsGranted('ROLE_VISIT_TYPE_LIST')]
  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(UserData $userData): Response {
    return $this->render(
        'visit/type/list/list.html.twig',
        ['visitTypesDtoList' => $this->visitTypeService->getList($userData->getCompanyId())]);
  }

  #[IsGranted('ROLE_VISIT_TYPE_DETAILS')]
  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $id, UserData $userData): Response {
    $visitTypeDetailsDto = $this->visitTypeService->getDetails(
        $id,
        $userData->getCompanyId());

    if ($visitTypeDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'visit/type/details/details.html.twig',
        ['visitTypeDetailsDto' => $visitTypeDetailsDto]);
  }

  #[IsGranted('ROLE_VISIT_TYPE_CREATE')]
  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(UserData $userData, Request $request): Response {
    $visitTypeCreateRequest =
        (new VisitTypeCreateRequest())->setCompanyId($userData->getCompanyId());

    $form =
        $this->createForm(
            VisitTypeCreateType::class,
            $visitTypeCreateRequest);
    $form->handleRequest($request);

    if ($visitTypeCreateRequest->getCompanyId() !== $userData->getCompanyId()) {
      throw new BadRequestHttpException();
    }

    if ($form->isSubmitted() && $form->isValid()) {
      $this->visitTypeService->create($visitTypeCreateRequest, $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('visit-type-added-successfully'));

      return $this->redirectToRoute('visit_type_list');
    }

    return $this->render('visit/type/create/create.html.twig', ['form' => $form]);
  }


  #[IsGranted('ROLE_VISIT_TYPE_UPDATE')]
  #[Route(path: '/{id}/update', name: 'update', requirements: ['id' => '\d+'], methods: [
      'GET',
      'PUT'])]
  public function update(int $id, UserData $userData, Request $request): Response {
    $visitTypeUpdateRequest =
        $this->visitTypeService->getUpdateRequest($id, $userData->getCompanyId());

    if ($visitTypeUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(VisitTypeUpdateType::class, $visitTypeUpdateRequest, ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($visitTypeUpdateRequest->getId() !== $id
        || $visitTypeUpdateRequest->getCompanyId() !== $userData->getCompanyId()) {
      throw new BadRequestHttpException();
    }

    if ($form->isSubmitted() && $form->isValid()) {
      $this->visitTypeService->update($id, $visitTypeUpdateRequest, $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('visit-type-edited-successfully'));

      return $this->redirectToRoute('visit_type_list');
    }

    return $this->render('visit/type/update/update.html.twig', ['form' => $form]);
  }

  #[IsGranted('ROLE_VISIT_TYPE_ARCHIVE')]
  #[Route(path: '/{id}/archive', name: 'archive', requirements: ['id' => '\d+'], methods: ['PUT'])]
  public function archive(int $id, UserData $userData): Response {
    $this->visitTypeService->archive($id, $userData->getCompanyId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('visit-type-archived-successfully'));

    return $this->redirectToRoute('visit_type_list');
  }

  #[IsGranted('ROLE_VISIT_TYPE_UN_ARCHIVE')]
  #[Route(path: '/{id}/un-archive', name: 'un_archive', requirements: ['id' => '\d+'], methods: ['PUT'])]
  public function unArchive(int $id, UserData $userData): Response {
    $this->visitTypeService->unArchive($id, $userData->getCompanyId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('visit-type-un-archived-successfully'));

    return $this->redirectToRoute('visit_type_list');
  }
}