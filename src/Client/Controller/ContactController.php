<?php

namespace App\Client\Controller;

use App\Client\Dto\ContactCreateRequest;
use App\Client\Form\Type\ContactCreateType;
use App\Client\Form\Type\ContactUpdateType;
use App\Client\Service\ClientService;
use App\Client\Service\ContactService;
use App\Common\Const\FlashMessageConst;
use App\Security\Dto\UserData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[IsGranted('ROLE_USER')]
#[Route(path: '/client/{clientId}/contact', name: 'client_contact_', requirements: ['clientId' => '\d+'])]
class ContactController extends AbstractController {

  public function __construct(
      private readonly ClientService $clientService,
      private readonly ContactService $contactService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(int $clientId, UserData $userData): Response {
    $clientDetailsDto = $this->clientService->getDetails($clientId, $userData->getCompanyId());

    if ($clientDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'client/contact/list/list.html.twig',
        [
            'clientDetailsDto' => $clientDetailsDto,
            'contactsDtoList' => $this->contactService->getList(
                $clientId,
                $userData->getCompanyId())]);
  }

  #[Route(path: '/{id}/update', name: 'update', requirements: ['id' => '\d+'], methods: [
      'GET',
      'PUT'])]
  public function update(int $id, int $clientId, UserData $userData, Request $request): Response {
    $contactUpdateRequest = $this->contactService->getUpdateRequest($id, $userData->getCompanyId());

    if ($contactUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form = $this->createForm(ContactUpdateType::class, $contactUpdateRequest, ['method' =>'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->contactService->update(
          $id,
          $contactUpdateRequest,
          $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('client-contact-edited-successfully'));

      return $this->redirectToRoute('client_contact_list', ['clientId' => $clientId]);
    }

    return $this->render(
        'client/contact/update/update.html.twig',
        ['clientId' => $clientId, 'form' => $form]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(int $clientId, UserData $userData, Request $request): Response {
    $contactCreateRequest =
        (new ContactCreateRequest())
            ->setClientId($clientId)
            ->setCompanyId($userData->getCompanyId());

    $form = $this->createForm(ContactCreateType::class, $contactCreateRequest);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->contactService->create(
          $clientId,
          $contactCreateRequest,
          $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('client-contact-added-successfully'));

      return $this->redirectToRoute('client_contact_list', ['clientId' => $clientId]);
    }

    return $this->render(
        'client/contact/create/create.html.twig',
        ['clientId' => $clientId, 'form' => $form]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: [
      'GET',
      'DELETE'])]
  public function delete(int $id, int $clientId, UserData $userData): Response {
    $this->contactService->delete($id, $userData->getCompanyId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('client-contact-deleted-successfully'));

    return $this->redirectToRoute('client_contact_list', ['clientId' => $clientId]);
  }
}