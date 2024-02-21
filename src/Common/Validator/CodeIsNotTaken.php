<?php

namespace App\Common\Validator;

use App\Common\Entity\CompanyContextInterface;
use App\Common\Entity\Dictionary;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
class CodeIsNotTaken extends Constraint {

  /**
   * @template T of Dictionary&CompanyContextInterface
   * @param class-string<T> $entityClass
   */
  public function __construct(
      public readonly string $entityClass,
      public readonly bool $isCreate,
      mixed $options = null,
      ?array $groups = null,
      mixed $payload = null) {
    parent::__construct($options, $groups, $payload);
  }

  public function getMessage(): string {
    return 'code-is-already-taken';
  }

  public function getTargets(): string {
    return self::CLASS_CONSTRAINT;
  }

  public function validatedBy(): string {
    return static::class . ($this->isCreate ? 'Create' : 'Update') . 'Validator';
  }
}