<?php

namespace App\ClientSpecialist\Entity;

use App\Client\Entity\Client;
use App\ClientSpecialist\Repository\ClientSpecialistRepository;
use App\Common\Entity\ClientContextInterface;
use App\Common\Entity\CompanyContextInterface;
use App\Common\Entity\SoftDeleteEntity;
use App\Common\Entity\SpecialistContextInterface;
use App\Company\Entity\Company;
use App\Specialist\Entity\Specialist;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: ClientSpecialistRepository::class)]
#[Table(name: 'client_specialist', schema: 'client_specialist')]
class ClientSpecialist extends SoftDeleteEntity implements ClientContextInterface, SpecialistContextInterface, CompanyContextInterface {

  #[Column(name: 'from_date', type: Types::DATE_IMMUTABLE, nullable: true)]
  private ?DateTimeImmutable $fromDate = null;

  #[Column(name: 'to_date', type: Types::DATE_IMMUTABLE, nullable: true)]
  private ?DateTimeImmutable $toDate = null;

  #[Column(name: 'assign_type', type: Types::STRING, length: 10, nullable: false, enumType: AssignType::class)]
  private AssignType $assignType;

  #[JoinColumn(name: 'client_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Client::class, fetch: 'LAZY')]
  private Client $client;

  #[JoinColumn(name: 'specialist_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Specialist::class, fetch: 'LAZY')]
  private Specialist $specialist;

  #[JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Company::class, fetch: 'LAZY')]
  private Company $company;

  public function getFromDate(): ?DateTimeImmutable {
    return $this->fromDate;
  }

  public function setFromDate(?DateTimeImmutable $fromDate): self {
    $this->fromDate = $fromDate;

    return $this;
  }

  public function getToDate(): ?DateTimeImmutable {
    return $this->toDate;
  }

  public function setToDate(?DateTimeImmutable $toDate): self {
    $this->toDate = $toDate;

    return $this;
  }

  public function getAssignType(): AssignType {
    return $this->assignType;
  }

  public function setAssignType(AssignType $assignType): self {
    $this->assignType = $assignType;

    return $this;
  }

  public function getClient(): Client {
    return $this->client;
  }

  public function setClient(Client $client): self {
    $this->client = $client;

    return $this;
  }

  public function getSpecialist(): Specialist {
    return $this->specialist;
  }

  public function setSpecialist(Specialist $specialist): self {
    $this->specialist = $specialist;

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