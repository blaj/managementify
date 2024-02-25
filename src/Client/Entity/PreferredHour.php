<?php

namespace App\Client\Entity;

use App\Client\Repository\PreferredHourRepository;
use App\Common\Entity\ClientContextInterface;
use App\Common\Entity\CompanyContextInterface;
use App\Common\Entity\DayOfWeek;
use App\Common\Entity\SoftDeleteEntity;
use App\Company\Entity\Company;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: PreferredHourRepository::class)]
#[Table(name: 'preferred_hour', schema: 'client')]
class PreferredHour extends SoftDeleteEntity
    implements ClientContextInterface, CompanyContextInterface {

  #[Column(name: 'from_time', type: Types::TIME_IMMUTABLE, nullable: false)]
  private DateTimeImmutable $fromTime;

  #[Column(name: 'to_time', type: Types::TIME_IMMUTABLE, nullable: false)]
  private DateTimeImmutable $toTime;

  #[Column(name: 'day_of_week', type: Types::STRING, length: 10, nullable: false, enumType: DayOfWeek::class)]
  private DayOfWeek $dayOfWeek;

  #[JoinColumn(name: 'client_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Client::class, fetch: 'LAZY', inversedBy: 'preferredHours')]
  private Client $client;

  #[JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Company::class, fetch: 'LAZY')]
  private Company $company;

  public function getFromTime(): DateTimeImmutable {
    return $this->fromTime;
  }

  public function setFromTime(DateTimeImmutable $fromTime): self {
    $this->fromTime = $fromTime;

    return $this;
  }

  public function getToTime(): DateTimeImmutable {
    return $this->toTime;
  }

  public function setToTime(DateTimeImmutable $toTime): self {
    $this->toTime = $toTime;

    return $this;
  }

  public function getDayOfWeek(): DayOfWeek {
    return $this->dayOfWeek;
  }

  public function setDayOfWeek(DayOfWeek $dayOfWeek): self {
    $this->dayOfWeek = $dayOfWeek;

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