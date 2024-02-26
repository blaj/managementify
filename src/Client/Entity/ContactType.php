<?php

namespace App\Client\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ContactType: string implements TranslatableInterface {

  case EMAIL = 'EMAIL';
  case PHONE = 'PHONE';
  case OTHER = 'OTHER';

  public function trans(TranslatorInterface $translator, string $locale = null): string {
    return $translator->trans(strtolower($this->value), locale: $locale);
  }
}
