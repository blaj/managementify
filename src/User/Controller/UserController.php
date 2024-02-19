<?php

namespace App\User\Controller;

use App\User\Dto\UserRegisterRequest;
use App\User\Form\Type\UserRegisterType;
use App\User\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/user', name: 'user_')]
class UserController extends AbstractController {

  public function __construct(private readonly UserService $userService) {}

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