<?php

namespace App\User\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\PaginatedList\Dto\PageCriteria;
use App\Security\Dto\UserData;
use App\User\Dto\UserCreateRequest;
use App\User\Dto\UserPaginatedListCriteria;
use App\User\Dto\UserPaginatedListFilter;
use App\User\Dto\UserRegisterRequest;
use App\User\Form\Type\UserCreateType;
use App\User\Form\Type\UserPaginatedListCriteriaType;
use App\User\Form\Type\UserRegisterType;
use App\User\Form\Type\UserUpdateType;
use App\User\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/user', name: 'user_')]
class UserController extends AbstractController {

  public function __construct(private readonly UserService $userService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(UserData $userData, Request $request): Response {
    $userPaginatedListCriteria = new UserPaginatedListCriteria(UserPaginatedListFilter::class);
    $userPaginatedListCriteria->setPageCriteria(PageCriteria::default());

    $form = $this->createForm(UserPaginatedListCriteriaType::class, $userPaginatedListCriteria);
    $form->handleRequest($request);

    return $this->render(
        'user/list/list.html.twig',
        [
            'form' => $form,
            'usersDtoPaginatedList' => $this->userService->getPaginatedListByCriteria(
                $userPaginatedListCriteria,
                $userData->getCompanyId()),
            'sortableFields' => UserPaginatedListCriteria::sortableFields]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $id, UserData $userData): Response {
    $userDetailsDto = $this->userService->getDetails($id, $userData->getCompanyId());

    if ($userDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render('user/details/details.html.twig', ['userDetailsDto' => $userDetailsDto]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(UserData $userData, Request $request): Response {
    $userCreateRequest = new UserCreateRequest();

    $form =
        $this->createForm(
            UserCreateType::class,
            $userCreateRequest,
            ['companyId' => $userData->getCompanyId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->userService->create($userCreateRequest, $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('user-added-successfully'));

      return $this->redirectToRoute('user_list');
    }

    return $this->render('user/create/create.html.twig', ['form' => $form]);
  }

  #[Route(path: '/{id}/update', name: 'update', requirements: ['id' => '\d+'], methods: [
      'GET',
      'PUT'])]
  public function update(int $id, UserData $userData, Request $request): Response {
    $userUpdateRequest = $this->userService->getUpdateRequest($id, $userData->getCompanyId());

    if ($userUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            UserUpdateType::class,
            $userUpdateRequest,
            ['method' => 'PUT', 'companyId' => $userData->getCompanyId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->userService->update($id, $userUpdateRequest, $userData->getCompanyId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('user-edited-successfully'));

      return $this->redirectToRoute('user_list');
    }

    return $this->render('user/update/update.html.twig', ['form' => $form]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
  public function delete(int $id, UserData $userData): Response {
    $this->userService->delete($id, $userData->getCompanyId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('user-deleted-successfully'));

    return $this->redirectToRoute('user_list');
  }

  #[IsGranted(new Expression("!is_authenticated()"))]
  #[Route(path: '/register', name: 'register', methods: ['GET', 'POST'])]
  public function register(Request $request): Response {
    $form =
        $this->createForm(
            UserRegisterType::class,
            $userRegisterRequest = new UserRegisterRequest());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->userService->register($userRegisterRequest);

      return $this->redirectToRoute('security_login');
    }

    return $this->render('user/register/register.html.twig', ['form' => $form]);
  }
}