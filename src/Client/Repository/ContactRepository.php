<?php

namespace App\Client\Repository;

use App\Client\Entity\Contact;
use App\Common\Repository\AbstractSoftDeleteClientCompanyContextRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSoftDeleteClientCompanyContextRepository<Contact>
 */
class ContactRepository extends AbstractSoftDeleteClientCompanyContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Contact::class);
  }
}