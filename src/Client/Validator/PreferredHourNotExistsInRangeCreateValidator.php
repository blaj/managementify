<?php

namespace App\Client\Validator;

use App\Client\Dto\PreferredHourCreateRequest;
use App\Client\Repository\PreferredHourRepository;
use App\Common\Dto\DateTimeImmutableRange;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

class PreferredHourNotExistsInRangeCreateValidator extends ConstraintValidator {

  public function __construct(
      private readonly PreferredHourRepository $preferredHourRepository,
      private readonly TranslatorInterface $translator) {}

  public function validate(mixed $value, Constraint $constraint): void {
    if (!$constraint instanceof PreferredHourNotExistsInRange) {
      throw new UnexpectedTypeException($constraint, PreferredHourNotExistsInRange::class);
    }

    if (!$value instanceof PreferredHourCreateRequest) {
      throw new UnexpectedValueException($value, PreferredHourCreateRequest::class);
    }

    foreach ($value->getDayOfWeeks() as $dayOfWeek) {
      if (!$this->preferredHourRepository->existsByClientIdAndDayOfWeekAndRangeOverlapAndCompanyId(
          $value->getClientId(),
          $dayOfWeek,
          (new DateTimeImmutableRange())
              ->setFrom($value->getFromTime())
              ->setTo($value->getToTime()),
          $value->getCompanyId())) {
        continue;
      }

      $this->context
          ->buildViolation($constraint->getMessage())
          ->setParameter('{{ day }}', $dayOfWeek->trans($this->translator))
          ->addViolation();
    }
  }
}