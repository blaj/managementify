<?php

namespace App\Common\Twig\Extension;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class YesOrNoExtension extends AbstractExtension {

  public function __construct(private readonly TranslatorInterface $translator) {}

  public function getFilters(): array {
    return [
        new TwigFilter('yesOrNo', [$this, 'yesOrNo'])
    ];
  }

  public function yesOrNo(bool $value): string {
    return $value ? $this->translator->trans('yes') : $this->translator->trans('no');
  }
}