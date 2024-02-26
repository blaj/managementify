<?php

namespace App\Client\Mapper;

use App\Client\Dto\ContactListItemDto;
use App\Client\Entity\Contact;

class ContactListItemDtoMapper {

  public static function map(?Contact $contact): ?ContactListItemDto {
    if ($contact === null) {
      return null;
    }

    return new ContactListItemDto($contact->getId(), $contact->getContent(), $contact->getType());
  }
}