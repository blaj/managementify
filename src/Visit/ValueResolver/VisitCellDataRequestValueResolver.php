<?php

namespace App\Visit\ValueResolver;

use App\Visit\Dto\VisitCellDataRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestPayloadValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class VisitCellDataRequestValueResolver implements ValueResolverInterface {

  public static string $sessionName = 'visit_cell_data_request';

  public function __construct(
      private readonly RequestPayloadValueResolver $requestPayloadValueResolver,
      private readonly RequestStack $requestStack) {}

  /**
   * @return iterable<int, VisitCellDataRequest>
   */
  public function resolve(Request $request, ArgumentMetadata $argument): iterable {
    $argumentType = $argument->getType();

    if ($argumentType === null || !is_a($argumentType, VisitCellDataRequest::class, true)) {
      return [];
    }

    $getData = $request->query->all();

    if (count($getData) === 0) {
      $getData = $this->requestStack->getSession()->get(self::$sessionName);

      if (is_array($getData)) {
        foreach ($getData as $key => $value) {
          $request->query->set($key, $value);
        }
      }
    }

    $this->requestStack->getSession()->set(self::$sessionName, $getData);

    return $this->requestPayloadValueResolver->resolve($request, $argument);
  }
}