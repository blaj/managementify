<?php

namespace App\Common\PaginatedList\Validator;

use App\Common\PaginatedList\Dto\PaginatedListCriteria;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class AllowedSortByFieldValidator extends ConstraintValidator {

  public function validate(mixed $value, Constraint $constraint): void  {
    if (!$constraint instanceof AllowedSortByField) {
      throw new UnexpectedTypeException($constraint, AllowedSortByField::class);
    }

    if ($value === null || $value === '') {
      return;
    }

    if (!$value instanceof PaginatedListCriteria) {
      throw new UnexpectedValueException($value, PaginatedListCriteria::class);
    }

    if ($value->getSort() === null) {
      return;
    }

    if ($value->getSort()->getBy() === null) {
      return;
    }

    if (in_array($value->getSort()->getBy(), $constraint->fields, true)) {
      return;
    }

    $this->context->buildViolation($constraint->message)->addViolation();
  }
}