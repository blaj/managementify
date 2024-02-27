<?php

namespace App\User\Dto;

readonly class UserListItemDto {

  public function __construct(public int $id, public string $username, public string $email) {}
}