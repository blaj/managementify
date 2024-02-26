<?php

namespace App\Client\Mapper;

use App\Client\Dto\ContactUpdateRequest;
use App\Client\Entity\Contact;

class ContactUpdateRequestMapper {

  public static function map(?Contact $contact): ?ContactUpdateRequest {
    if ($contact === null) {
      return null;
    }

    return (new ContactUpdateRequest())
        ->setId($contact->getId())
        ->setContent($contact->getContent())
        ->setType($contact->getType())
        ->setClientId($contact->getClient()->getId())
        ->setCompanyId($contact->getCompany()->getId());
  }
}