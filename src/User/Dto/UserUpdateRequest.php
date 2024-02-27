<?php

namespace App\User\Dto;

use App\Common\Dto\IdInterface;
use App\User\Validator\EmailIsFree;
use App\User\Validator\UsernameIsFree;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

#[EmailIsFree(isCreate: false)]
#[UsernameIsFree(isCreate: false)]
class UserUpdateRequest implements IdInterface, EmailInterface, UsernameInterface {

  private int $id;

  #[NotBlank]
  #[Length(min: 3, max: 50)]
  private string $username;

  #[NotBlank]
  #[Length(min: 3, max: 100)]
  #[Email]
  private string $email;

  #[Length(min: 8, max: 50)]
  #[NotCompromisedPassword]
  private ?string $password = null;

  private ?int $roleId = null;

  private ?int $specialistId = null;

  private ?int $clientId = null;

  public function getId(): int {
    return $this->id;
  }

  public function setId(int $id): self {
    $this->id = $id;

    return $this;
  }

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

  public function getPassword(): ?string {
    return $this->password;
  }

  public function setPassword(?string $password): self {
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