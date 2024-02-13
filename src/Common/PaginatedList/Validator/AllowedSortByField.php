<?php

namespace App\Common\PaginatedList\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
class AllowedSortByField extends Constraint {

  public string $message = 'Nie możesz sortować po tym polu.';

  /**
   * @var array<string>
   */
  public array $fields = [];

  /**
   * @param array<string> $fields
   */
  public function __construct(array $fields, array $groups = null, mixed $payload = null) {
    parent::__construct([], $groups, $payload);

    $this->fields = $fields;
  }

  public function getTargets(): string {
    return self::CLASS_CONSTRAINT;
  }
}