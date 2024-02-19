<?php

namespace App\User\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class UsernameIsFree extends Constraint {

  public function getMessage(): string {
    return 'username-is-already-taken';
  }

  public function getTargets(): string {
    return self::PROPERTY_CONSTRAINT;
  }
}