<?php

namespace App\Client\Controller;

use App\Client\Dto\ClientCreateRequest;
use App\Client\Dto\ClientPaginatedListCriteria;
use App\Client\Dto\ClientPaginatedListFilter;
use App\Client\Form\Type\ClientCreateType;
use App\Client\Form\Type\ClientPaginatedListCriteriaType;
use App\Client\Form\Type\ClientUpdateType;
use App\Client\Service\ClientService;
use App\Common\Const\FlashMessageConst;
use App\Common\PaginatedList\Dto\PageCriteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[IsGranted('ROLE_USER')]
#[Route(path: '/client', name: 'client_')]
class ClientController extends AbstractController {

  public function __construct(private readonly ClientService $clientService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(Request $request): Response {
    $clientPaginatedListCriteria =
        new ClientPaginatedListCriteria(ClientPaginatedListFilter::class);
    $clientPaginatedListCriteria->setPageCriteria(PageCriteria::default());

    $form = $this->createForm(ClientPaginatedListCriteriaType::class, $clientPaginatedListCriteria);
    $form->handleRequest($request);

    return $this->render(
        'client/list/list.html.twig',
        [
            'form' => $form,
            'clientsDtoPaginatedList' => $this->clientService->getPaginatedListByCriteria(
                $clientPaginatedListCriteria),
            'sortableFields' => ClientPaginatedListCriteria::sortableFields]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $id): Response {
    $clientDetailsDto = $this->clientService->getDetails($id);

    if ($clientDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'client/details/details.html.twig',
        ['clientDetailsDto' => $clientDetailsDto]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(Request $request): Response {
    $form =
        $this->createForm(
            ClientCreateType::class,
            $clientCreateRequest = new ClientCreateRequest());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->clientService->create($clientCreateRequest);

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('client-added-successfully'));

      return $this->redirectToRoute('client_list');
    }

    return $this->render('client/create/create.html.twig', ['form' => $form]);
  }

  #[Route(path: '/{id}/update', name: 'update', requirements: ['id' => '\d+'], methods: [
      'GET',
      'PUT'])]
  public function update(int $id, Request $request): Response {
    $clientUpdateRequest = $this->clientService->getUpdateRequest($id);

    if ($clientUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form = $this->createForm(ClientUpdateType::class, $clientUpdateRequest, ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->clientService->update($id, $clientUpdateRequest);

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('client-edited-successfully'));

      return $this->redirectToRoute('client_list');
    }

    return $this->render('client/update/update.html.twig', ['form' => $form]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
  public function delete(int $id): Response {
    $this->clientService->delete($id);

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('client-deleted-successfully'));

    return $this->redirectToRoute('client_list');
  }
}