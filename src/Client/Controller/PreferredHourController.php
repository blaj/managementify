<?php

namespace App\Client\Controller;

use App\Client\Dto\PreferredHourCreateRequest;
use App\Client\Form\Type\PreferredHourCreateType;
use App\Client\Form\Type\PreferredHourUpdateType;
use App\Client\Service\ClientService;
use App\Client\Service\PreferredHourService;
use App\Common\Const\FlashMessageConst;
use App\Security\Dto\UserData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/client/{clientId}/preferred-hour', name: 'client_preferred_hour_', requirements: ['clientId' => '\d+'])]
class PreferredHourController extends AbstractController {

  public function __construct(
      private readonly ClientService $clientService,
      private readonly PreferredHourService $preferredHourService) {}

  #[IsGranted('ROLE_CLIENT_PREFERRED_HOUR_LIST')]
  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(int $clientId, UserData $userData): Response {
    $clientDetailsDto = $this->clientService->getDetails($clientId, $userData->getCompanyId());

    if ($clientDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'client/preferred-hour/list/list.html.twig',
        [
            'clientDetailsDto' => $clientDetailsDto,
            'preferredHoursGroupsDtoList' => $this->preferredHourService->getListGroup(
                $clientId,
                $userData->getCompanyId())]);
  }

  #[IsGranted('ROLE_CLIENT_PREFERRED_HOUR_CREATE')]
  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(int $clientId, UserData $userData, Request $request): Response {
    $preferredHourCreateRequest =
        (new PreferredHourCreateRequest())
            ->setClientId($clientId)
            ->setCompanyId($userData->getCompanyId());

    $form = $this->createForm(PreferredHourCreateType::class, $preferredHourCreateRequest);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->preferredHourService->create(
          $clientId,
          $preferredHourCreateRequest,
          $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('client-preferred-hour-added-successfully'));

      return $this->redirectToRoute('client_preferred_hour_list', ['clientId' => $clientId]);
    }

    return $this->render(
        'client/preferred-hour/create/create.html.twig',
        ['clientId' => $clientId, 'form' => $form]);
  }

  #[IsGranted('ROLE_CLIENT_PREFERRED_HOUR_UPDATE')]
  #[Route(path: '/{id}/update', name: 'update', requirements: ['id' => '\d+'], methods: [
      'GET',
      'PUT'])]
  public function update(int $id, int $clientId, UserData $userData, Request $request): Response {
    $preferredHourUpdateRequest =
        $this->preferredHourService->getUpdateRequest($id, $userData->getCompanyId());

    if ($preferredHourUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            PreferredHourUpdateType::class,
            $preferredHourUpdateRequest,
            ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->preferredHourService->update(
          $id,
          $preferredHourUpdateRequest,
          $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('client-preferred-hour-edited-successfully'));

      return $this->redirectToRoute('client_preferred_hour_list', ['clientId' => $clientId]);
    }

    return $this->render(
        'client/preferred-hour/update/update.html.twig',
        ['clientId' => $clientId, 'form' => $form]);
  }

  #[IsGranted('ROLE_CLIENT_PREFERRED_HOUR_DELETE')]
  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: [
      'GET',
      'DELETE'])]
  public function delete(int $id, int $clientId, UserData $userData): Response {
    $this->preferredHourService->delete($id, $userData->getCompanyId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('client-preferred-hour-deleted-successfully'));

    return $this->redirectToRoute('client_preferred_hour_list', ['clientId' => $clientId]);
  }
}