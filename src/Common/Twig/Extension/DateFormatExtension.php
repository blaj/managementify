<?php

namespace App\Common\Twig\Extension;

use DateTimeImmutable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateFormatExtension extends AbstractExtension {

  public function getFilters(): array {
    return [
        new TwigFilter('dateFormat', [$this, 'dateFormat'])
    ];
  }

  public function dateFormat(?DateTimeImmutable $date, string $format = 'Y-m-d'): string {
    if ($date === null) {
      return '';
    }

    return $date->format($format);
  }
}