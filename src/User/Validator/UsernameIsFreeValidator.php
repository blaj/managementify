<?php

namespace App\User\Validator;

use App\User\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UsernameIsFreeValidator extends ConstraintValidator {

  public function __construct(private readonly UserRepository $userRepository) {}

  public function validate(mixed $value, Constraint $constraint): void {
    if (!$constraint instanceof UsernameIsFree) {
      throw new UnexpectedTypeException($constraint, UsernameIsFree::class);
    }

    if (!is_string($value)) {
      throw new UnexpectedValueException($value, 'string');
    }

    if (strlen($value) === 0) {
      return;
    }

    if (!$this->userRepository->existsByUsername($value)) {
      return;
    }

    $this->context
        ->buildViolation($constraint->getMessage())
        ->addViolation();
  }
}