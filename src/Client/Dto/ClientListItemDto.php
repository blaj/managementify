<?php

namespace App\Client\Dto;

readonly class ClientListItemDto {

  public function __construct(
      public int $id,
      public string $firstname,
      public string $surname,
      public ?string $foreignId) {}
}