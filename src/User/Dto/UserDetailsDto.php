<?php

namespace App\User\Dto;

class UserDetailsDto {

  public function __construct(
      public int $id,
      public string $username,
      public string $email,
      public ?string $roleName,
      public ?string $specialistName,
      public ?string $clientName) {}
}