<?php

namespace App\Common\Twig\Extension;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GetActualRouteNameExtension extends AbstractExtension {

  public function __construct(private readonly RequestStack $requestStack) {}

  public function getFunctions(): array {
    return[
        new TwigFunction('getActualRouteName', [$this, 'getActualRouteName'])
    ];
  }

  public function getActualRouteName(): string {
    $request = $this->requestStack->getCurrentRequest();

    if ($request === null) {
      return '';
    }

    $route = $request->attributes->get('_route', '');

    return is_string($route) ? $route : '';
  }
}