<?php

namespace App\Security\Dto;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserData implements UserInterface, PasswordAuthenticatedUserInterface {

  private string $userIdentifier;

  private string $password;

  private int $companyId;

  public function eraseCredentials(): void {
  }

  /**
   * @return array<string>
   */
  public function getRoles(): array {
    return ['ROLE_USER'];
  }

  public function getUserIdentifier(): string {
    return $this->userIdentifier;
  }

  public function setUserIdentifier(string $userIdentifier): self {
    $this->userIdentifier = $userIdentifier;

    return $this;
  }

  public function getPassword(): string {
    return $this->password;
  }

  public function setPassword(string $password): self {
    $this->password = $password;

    return $this;
  }

  public function getCompanyId(): int {
    return $this->companyId;
  }

  public function setCompanyId(int $companyId): self {
    $this->companyId = $companyId;

    return $this;
  }
}