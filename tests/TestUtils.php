<?php

namespace App\Tests;

use Closure;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use RuntimeException;

abstract class TestUtils {

  public static function withConsecutive(array $parameterGroups): Closure {
    $constraints = [];

    foreach ($parameterGroups as $index => $parameters) {
      foreach ($parameters as $parameter) {
        if (!$parameter instanceof Constraint) {
          $parameter = new IsEqual($parameter);
        }

        $constraints[$index][] = $parameter;
      }
    }

    return function(...$args) use (&$constraints) {
      $arrayOfConstraints = array_shift($constraints);

      if (!is_array($arrayOfConstraints) || count($arrayOfConstraints) === 0) {
        throw new RuntimeException('Constraints array is empty or null.');
      }

      $numberOfConstraints = count($arrayOfConstraints);

      if (count($args) !== $numberOfConstraints) {
        throw new RuntimeException('Parameters count must match in all groups');
      }

      for ($i = 0; $i < $numberOfConstraints; $i++) {
        $arrayOfConstraints[$i]->evaluate($args[$i]);
      }
    };
  }
}