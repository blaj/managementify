<?php

namespace App\User\Validator;

use App\Common\Dto\IdInterface;
use App\Common\Utils\ReflectionUtils;
use App\User\Dto\EmailInterface;
use App\User\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class EmailIsFreeUpdateValidator extends ConstraintValidator {

  public function __construct(private readonly UserRepository $userRepository) {}

  public function validate(mixed $value, Constraint $constraint): void {
    if (!$constraint instanceof EmailIsFree) {
      throw new UnexpectedTypeException($constraint, EmailIsFree::class);
    }

    if (!is_object($value)
        || !ReflectionUtils::implementsInterfaces(
            $value::class,
            [IdInterface::class, EmailInterface::class])) {
      throw new UnexpectedValueException($value, IdInterface::class . '&' . EmailInterface::class);
    }

    /** @var IdInterface&EmailInterface $value */

    if (!$this->userRepository->existsByNotIdAndEmail($value->getId(), $value->getEmail())) {
      return;
    }

    $this->context
        ->buildViolation($constraint->getMessage())
        ->addViolation();
  }
}