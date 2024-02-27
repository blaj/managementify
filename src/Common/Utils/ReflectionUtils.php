<?php

namespace App\Common\Utils;

use ReflectionClass;
use ReflectionException;

class ReflectionUtils {

  /**
   * @template T of object
   * @param class-string<T> $class
   * @param array<string> $interfaces
   */
  public static function implementsInterfaces(string $class, array $interfaces): bool {
    foreach ($interfaces as $interface) {
      try {
        $reflectionClass = new ReflectionClass($class);
        /** @phpstan-ignore-next-line */
      } catch (ReflectionException $e) {
        return false;
      }

      if (!$reflectionClass->implementsInterface($interface)) {
        return false;
      }
    }

    return true;
  }
}