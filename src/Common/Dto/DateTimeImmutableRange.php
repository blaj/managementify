<?php

namespace App\Common\Dto;

use DateTimeImmutable;

class DateTimeImmutableRange {

  private ?DateTimeImmutable $from;

  private ?DateTimeImmutable $to;

  public function getFrom(): ?DateTimeImmutable {
    return $this->from;
  }

  public function setFrom(?DateTimeImmutable $from): self {
    $this->from = $from;

    return $this;
  }

  public function getTo(): ?DateTimeImmutable {
    return $this->to;
  }

  public function setTo(?DateTimeImmutable $to): self {
    $this->to = $to;

    return $this;
  }
}