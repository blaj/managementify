<?php

namespace App\Tests\User\Validator;

use App\User\Repository\UserRepository;
use App\User\Validator\EmailIsFree;
use App\User\Validator\EmailIsFreeValidator;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class EmailIsFreeValidatorTest extends ConstraintValidatorTestCase {

  private UserRepository $userRepository;

  public function setUp(): void {
    $this->userRepository = $this->createMock(UserRepository::class);

    parent::setUp();
  }

  public function createValidator(): EmailIsFreeValidator {
    return new EmailIsFreeValidator($this->userRepository);
  }

  /**
   * @test
   */
  public function givenWrongConstraintInstance_whenValidate_shouldThrowException(): void {
    $this->expectException(UnexpectedTypeException::class);

    // given
    $constraint = new Blank();

    // when
    $this->validator->validate(null, $constraint);

    // then
  }

  /**
   * @test
   */
  public function givenNonStringValueValue_whenValidate_shouldThrowException(): void {
    $this->expectException(UnexpectedValueException::class);

    // given
    $value = 123;

    // when
    $this->validator->validate($value, new EmailIsFree());

    // then
  }

  /**
   * @test
   */
  public function givenEmptyValue_whenValidate_shouldNoAssert(): void {
    // given
    $value = '';

    // when
    $this->validator->validate($value, new EmailIsFree());

    // then
    $this->assertNoViolation();
  }

  /**
   * @test
   */
  public function givenNonExistingEmail_whenValidate_shouldNoAssert(): void {
    // given
    $value = 'email@example.com';

    $this->userRepository
        ->expects(static::once())
        ->method('existsByEmail')
        ->with($value)
        ->willReturn(false);

    // when
    $this->validator->validate($value, new EmailIsFree());

    // then
    $this->assertNoViolation();
  }

  /**
   * @test
   */
  public function givenExistingEmail_whenValidate_shouldAssert(): void {
    // given
    $value = 'email@example.com';

    $this->userRepository
        ->expects(static::once())
        ->method('existsByEmail')
        ->with($value)
        ->willReturn(true);

    // when
    $this->validator->validate($value, new EmailIsFree());

    // then
    $this->buildViolation('email-is-already-taken')->assertRaised();
  }
}