<?php

namespace App\User\Validator;

use App\Common\Utils\ReflectionUtils;
use App\User\Dto\UsernameInterface;
use App\User\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UsernameIsFreeCreateValidator extends ConstraintValidator {

  public function __construct(private readonly UserRepository $userRepository) {}

  public function validate(mixed $value, Constraint $constraint): void {
    if (!$constraint instanceof UsernameIsFree) {
      throw new UnexpectedTypeException($constraint, UsernameIsFree::class);
    }

    if (!is_object($value)
        || !ReflectionUtils::implementsInterfaces(
            $value::class,
            [UsernameInterface::class])) {
      throw new UnexpectedValueException($value, UsernameInterface::class);
    }

    /** @var UsernameInterface $value */

    if (!$this->userRepository->existsByUsername($value->getUsername())) {
      return;
    }

    $this->context
        ->buildViolation($constraint->getMessage())
        ->addViolation();
  }
}