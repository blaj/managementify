<?php

namespace App\Common\Validator;

use App\Common\Dto\CodeInterface;
use App\Common\Dto\CompanyIdInterface;
use App\Common\Service\DictionaryExistsService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CodeIsNotTakenCreateValidator extends ConstraintValidator {

  public function __construct(private readonly DictionaryExistsService $dictionaryExistsService) {}

  public function validate(mixed $value, Constraint $constraint): void {
    if (!$constraint instanceof CodeIsNotTaken) {
      throw new UnexpectedTypeException($constraint, CodeIsNotTaken::class);
    }

    if (!is_object($value)) {
      throw new UnexpectedValueException($value, 'object');
    }

    $implementsInterfaces = class_implements($value::class);

    if (!in_array(CodeInterface::class, $implementsInterfaces, true)) {
      throw new UnexpectedValueException($value, CodeInterface::class);
    }

    if (!in_array(CompanyIdInterface::class, $implementsInterfaces, true)) {
      throw new UnexpectedValueException($value, CompanyIdInterface::class);
    }

    /** @var CodeInterface&CompanyIdInterface $value */

    if (!$this->dictionaryExistsService->existsByCodeAndCompanyId($constraint->entityClass, $value->getCode(), $value->getCompanyId())) {
      return;
    }

    $this->context
        ->buildViolation($constraint->getMessage())
        ->addViolation();
  }
}