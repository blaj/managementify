<?php

namespace App\Common\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class NotYetImplementedException extends HttpException {

  /**
   * @param array<string> $headers
   */
  public function __construct(
      string $message = '',
      ?Throwable $previous = null,
      int $code = 0,
      array $headers = []) {
    parent::__construct(501, $message, $previous, $headers, $code);
  }
}