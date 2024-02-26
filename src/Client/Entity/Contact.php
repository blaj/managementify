<?php

namespace App\Client\Entity;

use App\Client\Repository\ContactRepository;
use App\Common\Entity\ClientContextInterface;
use App\Common\Entity\CompanyContextInterface;
use App\Common\Entity\SoftDeleteEntity;
use App\Company\Entity\Company;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: ContactRepository::class)]
#[Table(name: 'contact', schema: 'client')]
class Contact extends SoftDeleteEntity implements ClientContextInterface, CompanyContextInterface {

  #[Column(name: 'content', type: Types::STRING, length: 100, nullable: false)]
  private string $content;

  #[Column(name: 'type', type: Types::STRING, length: 20, nullable: false, enumType: ContactType::class)]
  private ContactType $type;

  #[JoinColumn(name: 'client_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Client::class, fetch: 'LAZY', inversedBy: 'preferredHours')]
  private Client $client;

  #[JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Company::class, fetch: 'LAZY')]
  private Company $company;

  public function getContent(): string {
    return $this->content;
  }

  public function setContent(string $content): self {
    $this->content = $content;

    return $this;
  }

  public function getType(): ContactType {
    return $this->type;
  }

  public function setType(ContactType $type): self {
    $this->type = $type;

    return $this;
  }

  public function getClient(): Client {
    return $this->client;
  }

  public function setClient(Client $client): self {
    $this->client = $client;

    return $this;
  }

  public function getCompany(): Company {
    return $this->company;
  }

  public function setCompany(Company $company): self {
    $this->company = $company;

    return $this;
  }
}