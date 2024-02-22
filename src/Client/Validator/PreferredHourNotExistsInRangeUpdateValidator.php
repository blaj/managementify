<?php

namespace App\Client\Validator;

use App\Client\Dto\PreferredHourUpdateRequest;
use App\Client\Repository\PreferredHourRepository;
use App\Common\Dto\DateTimeImmutableRange;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

class PreferredHourNotExistsInRangeUpdateValidator extends ConstraintValidator {

  public function __construct(
      private readonly PreferredHourRepository $preferredHourRepository,
      private readonly TranslatorInterface $translator) {}

  public function validate(mixed $value, Constraint $constraint): void {
    if (!$constraint instanceof PreferredHourNotExistsInRange) {
      throw new UnexpectedTypeException($constraint, PreferredHourNotExistsInRange::class);
    }

    if (!$value instanceof PreferredHourUpdateRequest) {
      throw new UnexpectedValueException($value, PreferredHourUpdateRequest::class);
    }

    $preferredHour =
        $this->preferredHourRepository->findOneByIdAndCompany(
            $value->getId(),
            $value->getCompanyId());

    if ($preferredHour === null) {
      return;
    }

    if (!$this->preferredHourRepository->existsByNotIdAndClientIdAndDayOfWeekAndRangeOverlapAndCompanyId(
        $value->getId(),
        $value->getClientId(),
        $preferredHour->getDayOfWeek(),
        (new DateTimeImmutableRange())
            ->setFrom($value->getFromTime())
            ->setTo($value->getToTime()),
        $value->getCompanyId())) {
      return;
    }

    $this->context
        ->buildViolation($constraint->getMessage())
        ->setParameter('{{ day }}', $preferredHour->getDayOfWeek()->trans($this->translator))
        ->addViolation();
  }
}