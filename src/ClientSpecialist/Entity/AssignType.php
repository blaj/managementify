<?php

namespace App\ClientSpecialist\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AssignType: string implements TranslatableInterface {

  case MAIN = 'MAIN';
  case SECONDARY = 'SECONDARY';

  public function trans(TranslatorInterface $translator, string $locale = null): string {
    return $translator->trans(strtolower($this->value), locale: $locale);
  }
}