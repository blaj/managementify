<?php

namespace App\Security\Service;

use App\Security\Dto\UserData;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @implements UserProviderInterface<UserData>
 */
class UserDataProviderService implements UserProviderInterface {

  public function __construct(private readonly UserRepository $userRepository) {}

  /**
   * @throws NonUniqueResultException
   */
  public function loadUserByIdentifier(string $identifier): UserData {
    $user = $this->userRepository->findOneByUsername($identifier);

    if ($user === null) {
      $userNotFoundException =
          new UserNotFoundException(sprintf('User "%s" not found.', $identifier));
      $userNotFoundException->setUserIdentifier($identifier);

      throw $userNotFoundException;
    }

    return self::userData($user);
  }

  /**
   * @throws NonUniqueResultException
   */
  public function refreshUser(UserInterface $user): UserData {
    if (!$user instanceof UserData) {
      throw new UnsupportedUserException('User instance is not supported.');
    }

    $refreshedUser = $this->userRepository->findOneByUsername($user->getUserIdentifier());

    if ($refreshedUser === null) {
      $userNotFoundException =
          new UserNotFoundException(sprintf('User "%s" not found.', $user->getUserIdentifier()));
      $userNotFoundException->setUserIdentifier($user->getUserIdentifier());

      throw $userNotFoundException;
    }

    return self::userData($refreshedUser);
  }

  public function supportsClass(string $class): bool {
    return $class === UserData::class || is_subclass_of($class, UserData::class);
  }

  private static function userData(User $user): UserData {
    return (new UserData())
        ->setUserIdentifier($user->getUsername())
        ->setPassword($user->getPassword())
        ->setCompanyId($user->getCompany()->getId());
  }
}