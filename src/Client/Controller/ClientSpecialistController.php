<?php

namespace App\Client\Controller;

use App\Client\Form\Type\ClientSpecialistCreateType;
use App\Client\Form\Type\ClientSpecialistUpdateType;
use App\Client\Service\ClientService;
use App\ClientSpecialist\Dto\ClientSpecialistCreateRequest;
use App\ClientSpecialist\Service\ClientSpecialistService;
use App\Common\Const\FlashMessageConst;
use App\Security\Dto\UserData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/client/{clientId}/specialist', name: 'client_specialist_', requirements: ['clientId' => '\d+'])]
class ClientSpecialistController extends AbstractController {

  public function __construct(
      private readonly ClientService $clientService,
      private readonly ClientSpecialistService $clientSpecialistService) {}

  #[IsGranted('ROLE_CLIENT_SPECIALIST_LIST')]
  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(int $clientId, UserData $userData): Response {
    $clientDetailsDto = $this->clientService->getDetails($clientId, $userData->getCompanyId());

    if ($clientDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'client/client-specialist/list/list.html.twig',
        [
            'clientDetailsDto' => $clientDetailsDto,
            'clientSpecialistsDtoList' => $this->clientSpecialistService->getListForClient(
                $clientId,
                $userData->getCompanyId())]);
  }

  #[IsGranted('ROLE_CLIENT_SPECIALIST_CREATE')]
  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(int $clientId, UserData $userData, Request $request): Response {
    $clientSpecialistCreateRequest =
        (new ClientSpecialistCreateRequest())
            ->setClientId($clientId)
            ->setCompanyId($userData->getCompanyId());

    $form =
        $this->createForm(
            ClientSpecialistCreateType::class,
            $clientSpecialistCreateRequest,
            ['companyId' => $userData->getCompanyId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->clientSpecialistService->createForClient(
          $clientId,
          $clientSpecialistCreateRequest,
          $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('client-specialist-added-successfully'));

      return $this->redirectToRoute('client_specialist_list', ['clientId' => $clientId]);
    }

    return $this->render(
        'client/client-specialist/create/create.html.twig',
        ['clientId' => $clientId, 'form' => $form]);
  }

  #[IsGranted('ROLE_CLIENT_SPECIALIST_UPDATE')]
  #[Route(path: '/{id}/update', name: 'update', requirements: ['id' => '\d+'], methods: [
      'GET',
      'PUT'])]
  public function update(int $id, int $clientId, UserData $userData, Request $request): Response {
    $clientSpecialistUpdateRequest =
        $this->clientSpecialistService->getUpdateRequest($id, $userData->getCompanyId());

    if ($clientSpecialistUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            ClientSpecialistUpdateType::class,
            $clientSpecialistUpdateRequest,
            ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->clientSpecialistService->update(
          $id,
          $clientSpecialistUpdateRequest,
          $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('client-specialist-edited-successfully'));

      return $this->redirectToRoute('client_specialist_list', ['clientId' => $clientId]);
    }

    return $this->render(
        'client/client-specialist/update/update.html.twig',
        ['clientId' => $clientId, 'form' => $form]);
  }

  #[IsGranted('ROLE_CLIENT_SPECIALIST_DELETE')]
  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: [
      'GET',
      'DELETE'])]
  public function delete(int $id, int $clientId, UserData $userData): Response {
    $this->clientSpecialistService->delete($id, $userData->getCompanyId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('client-specialist-deleted-successfully'));

    return $this->redirectToRoute('client_specialist_list', ['clientId' => $clientId]);
  }
}