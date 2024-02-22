<?php

namespace App\Common\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum DayOfWeek: string implements TranslatableInterface {

  case MONDAY = 'MONDAY';
  case TUESDAY = 'TUESDAY';
  case WEDNESDAY = 'WEDNESDAY';
  case THURSDAY = 'THURSDAY';
  case FRIDAY = 'FRIDAY';
  case SATURDAY = 'SATURDAY';
  case SUNDAY = 'SUNDAY';

  public function trans(TranslatorInterface $translator, string $locale = null): string {
    return $translator->trans(strtolower($this->value), locale: $locale);
  }
}
