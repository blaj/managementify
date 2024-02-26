<?php

namespace App\Client\Dto;

use App\Client\Entity\ContactType;

readonly class ContactListItemDto {

  public function __construct(public int $id, public string $content, public ContactType $type) {}
}