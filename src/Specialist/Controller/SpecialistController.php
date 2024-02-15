<?php

namespace App\Specialist\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\PaginatedList\Dto\PageCriteria;
use App\Security\Dto\UserData;
use App\Specialist\Dto\SpecialistCreateRequest;
use App\Specialist\Dto\SpecialistPaginatedListCriteria;
use App\Specialist\Dto\SpecialistPaginatedListFilter;
use App\Specialist\Form\Type\SpecialistCreateType;
use App\Specialist\Form\Type\SpecialistPaginatedListCriteriaType;
use App\Specialist\Form\Type\SpecialistUpdateType;
use App\Specialist\Service\SpecialistService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[IsGranted('ROLE_USER')]
#[Route(path: '/specialist', name: 'specialist_')]
class SpecialistController extends AbstractController {

  public function __construct(private readonly SpecialistService $specialistService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(UserData $userData, Request $request): Response {
    $specialistPaginatedListCriteria =
        new SpecialistPaginatedListCriteria(SpecialistPaginatedListFilter::class);
    $specialistPaginatedListCriteria->setPageCriteria(PageCriteria::default());

    $form =
        $this->createForm(
            SpecialistPaginatedListCriteriaType::class,
            $specialistPaginatedListCriteria);
    $form->handleRequest($request);

    return $this->render(
        'specialist/list/list.html.twig',
        [
            'form' => $form,
            'specialistsDtoPaginatedList' => $this->specialistService->getPaginatedListByCriteria(
                $specialistPaginatedListCriteria,
                $userData->getCompanyId()),
            'sortableFields' => SpecialistPaginatedListCriteria::sortableFields]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $id, UserData $userData): Response {
    $specialistDetailsDto = $this->specialistService->getDetails($id, $userData->getCompanyId());

    if ($specialistDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'specialist/details/details.html.twig',
        ['specialistDetailsDto' => $specialistDetailsDto]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(UserData $userData, Request $request): Response {
    $form =
        $this->createForm(
            SpecialistCreateType::class,
            $specialistCreateRequest = new SpecialistCreateRequest());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->specialistService->create($specialistCreateRequest, $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('specialist-added-successfully'));

      return $this->redirectToRoute('specialist_list');
    }

    return $this->render('specialist/create/create.html.twig', ['form' => $form]);
  }


  #[Route(path: '/{id}/update', name: 'update', requirements: ['id' => '\d+'], methods: [
      'GET',
      'PUT'])]
  public function update(int $id, UserData $userData, Request $request): Response {
    $specialistUpdateRequest =
        $this->specialistService->getUpdateRequest($id, $userData->getCompanyId());

    if ($specialistUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            SpecialistUpdateType::class,
            $specialistUpdateRequest,
            ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->specialistService->update($id, $specialistUpdateRequest, $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('specialist-edited-successfully'));

      return $this->redirectToRoute('specialist_list');
    }

    return $this->render('specialist/update/update.html.twig', ['form' => $form]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
  public function delete(int $id, UserData $userData): Response {
    $this->specialistService->delete($id, $userData->getCompanyId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('specialist-deleted-successfully'));

    return $this->redirectToRoute('specialist_list');
  }
}