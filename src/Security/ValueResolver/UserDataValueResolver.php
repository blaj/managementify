<?php

namespace App\Security\ValueResolver;

use App\Security\Dto\UserData;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserDataValueResolver implements ValueResolverInterface {

  private Security $security;

  public function __construct(Security $security) {
    $this->security = $security;
  }

  /**
   * @return iterable<int, UserInterface>
   */
  public function resolve(Request $request, ArgumentMetadata $argument): iterable {
    $argumentType = $argument->getType();

    if ($argumentType === null || !is_a($argumentType, UserData::class, true)) {
      return [];
    }

    $user = $this->security->getUser();

    if ($user === null) {
      throw new AccessDeniedException();
    }

    return [$user];
  }
}