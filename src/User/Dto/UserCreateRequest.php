<?php

namespace App\User\Dto;

use App\User\Validator\EmailIsFree;
use App\User\Validator\UsernameIsFree;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

#[EmailIsFree(isCreate: true)]
#[UsernameIsFree(isCreate: true)]
class UserCreateRequest implements EmailInterface, UsernameInterface {

  #[NotBlank]
  #[Length(min: 3, max: 50)]
  private string $username;

  #[NotBlank]
  #[Length(min: 3, max: 100)]
  #[Email]
  private string $email;

  #[NotBlank]
  #[Length(min: 8, max: 50)]
  #[NotCompromisedPassword]
  private string $password;

  private ?int $roleId = null;

  private ?int $specialistId = null;

  private ?int $clientId = null;

  public function getUsername(): string {
    return $this->username;
  }

  public function setUsername(string $username): self {
    $this->username = $username;

    return $this;
  }

  public function getEmail(): string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

    return $this;
  }

  public function getPassword(): string {
    return $this->password;
  }

  public function setPassword(string $password): self {
    $this->password = $password;

    return $this;
  }

  public function getRoleId(): ?int {
    return $this->roleId;
  }

  public function setRoleId(?int $roleId): self {
    $this->roleId = $roleId;

    return $this;
  }

  public function getSpecialistId(): ?int {
    return $this->specialistId;
  }

  public function setSpecialistId(?int $specialistId): self {
    $this->specialistId = $specialistId;

    return $this;
  }

  public function getClientId(): ?int {
    return $this->clientId;
  }

  public function setClientId(?int $clientId): self {
    $this->clientId = $clientId;

    return $this;
  }
}