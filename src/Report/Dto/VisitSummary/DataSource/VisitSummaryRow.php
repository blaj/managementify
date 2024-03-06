<?php

namespace App\Report\Dto\VisitSummary\DataSource;

use DateTimeImmutable;

readonly class VisitSummaryRow {

  public function __construct(
      public string $specialistName,
      public string $clientName,
      public DateTimeImmutable $fromTime,
      public DateTimeImmutable $toTime,
      public ?string $note,
      public ?string $visitTypeName,
      public ?int $preferredPrice) {}
}