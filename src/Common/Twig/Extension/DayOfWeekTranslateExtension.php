<?php

namespace App\Common\Twig\Extension;

use App\Common\Entity\DayOfWeek;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DayOfWeekTranslateExtension extends AbstractExtension {

  public function __construct(private readonly TranslatorInterface $translator) {}

  public function getFilters(): array {
    return [
        new TwigFilter('dayOfWeekTranslate', [$this, 'dayOfWeekTranslate'])
    ];
  }

  public function dayOfWeekTranslate(DayOfWeek $dayOfWeek): string {
    return $this->translator->trans(strtolower($dayOfWeek->value));
  }
}