<?php

namespace App\Visit\Entity;

use App\Client\Entity\Client;
use App\Common\Entity\SoftDeleteEntity;
use App\Specialist\Entity\Specialist;
use App\Visit\Repository\VisitRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: VisitRepository::class)]
#[Table(name: 'visit', schema: 'visit')]
class Visit extends SoftDeleteEntity {

  #[Column(name: 'from_time', type: Types::DATETIME_IMMUTABLE, nullable: false)]
  private DateTimeImmutable $fromTime;

  #[Column(name: 'to_time', type: Types::DATETIME_IMMUTABLE, nullable: false)]
  private DateTimeImmutable $toTime;

  #[Column(name: 'note', type: Types::TEXT, nullable: true)]
  private ?string $note = null;

  #[JoinColumn(name: 'client_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Client::class, fetch: 'LAZY', inversedBy: 'visits')]
  private Client $client;

  #[JoinColumn(name: 'specialist_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Specialist::class, fetch: 'LAZY', inversedBy: 'visits')]
  private Specialist $specialist;

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

  public function getNote(): ?string {
    return $this->note;
  }

  public function setNote(?string $note): self {
    $this->note = $note;

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
}