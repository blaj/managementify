<?php

namespace App\Tests\Common\PaginatedList\Validator;

use App\Common\PaginatedList\Dto\PaginatedListCriteria;
use App\Common\PaginatedList\Dto\Sort;
use App\Common\PaginatedList\Validator\AllowedSortByField;
use App\Common\PaginatedList\Validator\AllowedSortByFieldValidator;
use App\Specialist\Dto\SpecialistPaginatedListFilter;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class AllowedSortByFieldValidatorTest extends ConstraintValidatorTestCase {

  protected function createValidator(): AllowedSortByFieldValidator {
    return new AllowedSortByFieldValidator();
  }

  /**
   * @test
   */
  public function givenWrongConstraintInstance_whenValidate_shouldThrowException(): void {
    // given
    $this->expectException(UnexpectedTypeException::class);

    $constraint = new Blank();

    // when
    $this->validator->validate(null, $constraint);

    // then
  }

  /**
   * @test
   */
  public function givenNullValue_whenValidate_shouldNoAssert(): void {
    // given
    $value = null;

    // when
    $this->validator->validate($value, new AllowedSortByField([]));

    // then
    $this->assertNoViolation();
  }

  /**
   * @test
   */
  public function givenEmptyValue_whenValidate_shouldNoAssert(): void {
    // given
    $value = '';

    // when
    $this->validator->validate($value, new AllowedSortByField([]));

    // then
    $this->assertNoViolation();
  }

  /**
   * @test
   */
  public function givenWrongValueInstance_whenValidate_shouldThrowException(): void {
    // given
    $this->expectException(UnexpectedValueException::class);

    $value = 'wrongInstance';

    // when
    $this->validator->validate($value, new AllowedSortByField([]));

    // then
  }

  /**
   * @test
   */
  public function givenNullSort_whenValidate_shouldNoAssert(): void {
    // given
    $value = (new PaginatedListCriteria(SpecialistPaginatedListFilter::class))->setSort(null);

    // when
    $this->validator->validate($value, new AllowedSortByField([]));

    // then
    $this->assertNoViolation();
  }

  /**
   * @test
   */
  public function givenAllowedSort_whenValidate_shouldNoAssert(): void {
    // given
    $allowedSort = 'sort';

    $value =
        (new PaginatedListCriteria(SpecialistPaginatedListFilter::class))
            ->setSort((new Sort())->setBy($allowedSort));

    // when
    $this->validator->validate($value, new AllowedSortByField([$allowedSort]));

    // then
    $this->assertNoViolation();
  }

  /**
   * @test
   */
  public function givenNotAllowedSort_whenValidate_shouldAssert(): void {
    // given
    $notAllowedSort = 'sort';

    $value =
        (new PaginatedListCriteria(SpecialistPaginatedListFilter::class))
            ->setSort((new Sort())->setBy('test'));

    // when
    $this->validator->validate($value, new AllowedSortByField([$notAllowedSort]));

    // then
    $this->buildViolation('Nie możesz sortować po tym polu.')->assertRaised();
  }
}