<?php

namespace App\User\Validator;

use App\Common\Dto\IdInterface;
use App\Common\Utils\ReflectionUtils;
use App\User\Dto\UsernameInterface;
use App\User\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UsernameIsFreeUpdateValidator extends ConstraintValidator {

  public function __construct(private readonly UserRepository $userRepository) {}

  public function validate(mixed $value, Constraint $constraint): void {
    if (!$constraint instanceof UsernameIsFree) {
      throw new UnexpectedTypeException($constraint, UsernameIsFree::class);
    }

    if (!is_object($value)
        || !ReflectionUtils::implementsInterfaces(
            $value::class,
            [IdInterface::class, UsernameInterface::class])) {
      throw new UnexpectedValueException(
          $value,
          IdInterface::class . '&' . UsernameInterface::class);
    }

    /** @var IdInterface&UsernameInterface $value */

    if (!$this->userRepository->existsByNotIdAndUsername($value->getId(), $value->getUsername())) {
      return;
    }

    $this->context
        ->buildViolation($constraint->getMessage())
        ->addViolation();
  }
}