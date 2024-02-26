<?php

namespace App\User\Dto;

readonly class RoleListItemDto {

  public function __construct(
      public int $id,
      public string $code,
      public string $name,
      public bool $archived) {}
}