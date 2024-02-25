<?php

namespace App\ClientSpecialist\Validator;

use App\ClientSpecialist\Dto\ClientSpecialistCreateRequest;
use App\ClientSpecialist\Entity\AssignType;
use App\ClientSpecialist\Repository\ClientSpecialistRepository;
use App\Common\Dto\DateTimeImmutableRange;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class MainAssignTypeIsNotExistsCreateValidator extends ConstraintValidator {

  public function __construct(
      private readonly ClientSpecialistRepository $clientSpecialistRepository) {}

  public function validate(mixed $value, Constraint $constraint): void {
    if (!$constraint instanceof MainAssignTypeIsNotExists) {
      throw new UnexpectedTypeException($constraint, MainAssignTypeIsNotExists::class);
    }

    if (!$value instanceof ClientSpecialistCreateRequest) {
      throw new UnexpectedValueException($value, ClientSpecialistCreateRequest::class);
    }

    if (!$this->clientSpecialistRepository->existsByClientIdAndAssignTypeAndRangeOverlapAndCompanyId(
        $value->getClientId(),
        AssignType::MAIN,
        (new DateTimeImmutableRange())->setFrom($value->getFromDate())->setTo($value->getToDate()),
        $value->getCompanyId())) {
      return;
    }

    $this->context
        ->buildViolation($constraint->getMessage())
        ->addViolation();
  }
}