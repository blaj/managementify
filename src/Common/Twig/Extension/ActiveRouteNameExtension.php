<?php

namespace App\Common\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ActiveRouteNameExtension extends AbstractExtension {

  public function getFilters(): array {
    return [
        new TwigFilter('activeRouteName', [$this, 'activeRouteName'])
    ];
  }

  public function activeRouteName(
      string $actualRouteName,
      string $routeName,
      string $class): string {
    return strtoupper($actualRouteName) === strtoupper($routeName) ? $class : '';
  }
}