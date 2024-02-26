<?php

namespace App\User\Controller;

use App\Common\Const\FlashMessageConst;
use App\Security\Dto\UserData;
use App\User\Dto\RoleCreateRequest;
use App\User\Form\Type\RoleCreateType;
use App\User\Form\Type\RoleUpdateType;
use App\User\Service\RoleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/user/role', name: 'user_role_')]
class RoleController extends AbstractController {

  public function __construct(private readonly RoleService $roleService) {}

  #[IsGranted('ROLE_ROLE_LIST')]
  #[Route(path: '', name: 'list', methods: ['GET'])]
  public function list(UserData $userData): Response {
    return $this->render(
        'user/role/list/list.html.twig',
        ['rolesDtoList' => $this->roleService->getList($userData->getCompanyId())]);
  }

  #[IsGranted('ROLE_ROLE_DETAILS')]
  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $id, UserData $userData): Response {
    $roleDetailsDto = $this->roleService->getDetails($id, $userData->getCompanyId());

    if ($roleDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'user/role/details/details.html.twig',
        ['roleDetailsDto' => $roleDetailsDto]);
  }

  #[IsGranted('ROLE_ROLE_CREATE')]
  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(UserData $userData, Request $request): Response {
    $roleCreateRequest = new RoleCreateRequest();

    $form = $this->createForm(RoleCreateType::class, $roleCreateRequest);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
      $this->roleService->create($roleCreateRequest, $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('role-added-successfully'));

      return $this->redirectToRoute('user_role_list');
    }

    return $this->render('user/role/create/create.html.twig', ['form' => $form]);
  }

  #[IsGranted('ROLE_ROLE_CREATE')]
  #[Route(path: '/{id}/update', name: 'update', requirements: ['id' => '\d+'], methods: [
      'GET',
      'PUT'])]
  public function update(int $id, UserData $userData, Request $request): Response {
    $roleUpdateRequest = $this->roleService->getUpdateRequest($id, $userData->getCompanyId());

    if ($roleUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form = $this->createForm(RoleUpdateType::class, $roleUpdateRequest, ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->roleService->update($id, $roleUpdateRequest, $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('role-edited-successfully'));

      return $this->redirectToRoute('user_role_list');
    }

    return $this->render('user/role/update/update.html.twig', ['form' => $form]);
  }

  #[IsGranted('ROLE_ROLE_ARCHIVE')]
  #[Route(path: '/{id}/archive', name: 'archive', requirements: ['id' => '\d+'], methods: ['PUT'])]
  public function archive(int $id, UserData $userData): Response {
    $this->roleService->archive($id, $userData->getCompanyId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('role-archived-successfully'));

    return $this->redirectToRoute('user_role_list');
  }

  #[IsGranted('ROLE_ROLE_UN_ARCHIVE')]
  #[Route(path: '/{id}/un-archive', name: 'un_archive', requirements: ['id' => '\d+'], methods: ['PUT'])]
  public function unArchive(int $id, UserData $userData): Response {
    $this->roleService->unArchive($id, $userData->getCompanyId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('role-un-archived-successfully'));

    return $this->redirectToRoute('user_role_list');
  }
}