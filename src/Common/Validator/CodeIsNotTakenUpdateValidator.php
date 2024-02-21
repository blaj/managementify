<?php

namespace App\Common\Validator;

use App\Common\Dto\CodeInterface;
use App\Common\Dto\CompanyIdInterface;
use App\Common\Dto\IdInterface;
use App\Common\Service\DictionaryExistsService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CodeIsNotTakenUpdateValidator extends ConstraintValidator {

  public function __construct(private readonly DictionaryExistsService $dictionaryExistsService) {}

  public function validate(mixed $value, Constraint $constraint): void {
    if (!$constraint instanceof CodeIsNotTaken) {
      throw new UnexpectedValueException($constraint, CodeIsNotTaken::class);
    }

    if (!is_object($value)) {
      throw new UnexpectedValueException($value, 'object');
    }

    $implementsInterfaces = class_implements($value::class);

    if (!in_array(IdInterface::class, $implementsInterfaces, true)) {
      throw new UnexpectedValueException($value, IdInterface::class);
    }

    if (!in_array(CodeInterface::class, $implementsInterfaces, true)) {
      throw new UnexpectedValueException($value, CodeInterface::class);
    }

    if (!in_array(CompanyIdInterface::class, $implementsInterfaces, true)) {
      throw new UnexpectedValueException($value, CompanyIdInterface::class);
    }

    /** @var IdInterface&CodeInterface&CompanyIdInterface $value */

    if (!$this->dictionaryExistsService->existsByNotIdAndCodeAndCompanyId(
        $constraint->entityClass,
        $value->getId(),
        $value->getCode(),
        $value->getCompanyId())) {
      return;
    }

    $this->context
        ->buildViolation($constraint->getMessage())
        ->addViolation();
  }
}