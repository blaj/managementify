<?php

namespace App\Tests\User\Validator;

use App\User\Repository\UserRepository;
use App\User\Validator\UsernameIsFree;
use App\User\Validator\UsernameIsFreeValidator;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class UsernameIsFreeValidatorTest extends ConstraintValidatorTestCase {

  private UserRepository $userRepository;

  public function setUp(): void {
    $this->userRepository = $this->createMock(UserRepository::class);

    parent::setUp();
  }

  public function createValidator(): UsernameIsFreeValidator {
    return new UsernameIsFreeValidator($this->userRepository);
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
    $this->validator->validate($value, new UsernameIsFree());

    // then
  }

  /**
   * @test
   */
  public function givenEmptyValue_whenValidate_shouldNoAssert(): void {
    // given
    $value = '';

    // when
    $this->validator->validate($value, new UsernameIsFree());

    // then
    $this->assertNoViolation();
  }

  /**
   * @test
   */
  public function givenNonExistingUsername_whenValidate_shouldNoAssert(): void {
    // given
    $value = 'username';

    $this->userRepository
        ->expects(static::once())
        ->method('existsByUsername')
        ->with($value)
        ->willReturn(false);

    // when
    $this->validator->validate($value, new UsernameIsFree());

    // then
    $this->assertNoViolation();
  }

  /**
   * @test
   */
  public function givenExistingUsername_whenValidate_shouldAssert(): void {
    // given
    $value = 'username';

    $this->userRepository
        ->expects(static::once())
        ->method('existsByUsername')
        ->with($value)
        ->willReturn(true);

    // when
    $this->validator->validate($value, new UsernameIsFree());

    // then
    $this->buildViolation('username-is-already-taken')->assertRaised();
  }
}