<?php

namespace App\Tests\Security\ValueResolver;

use App\Security\Dto\UserData;
use App\Security\ValueResolver\UserDataValueResolver;
use App\User\Entity\User;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserDataValueResolverTest extends TestCase {

  private UserDataValueResolver $userDataValueResolver;

  private Security $security;

  protected function setUp(): void {
    $this->security = $this->createMock(Security::class);

    $this->userDataValueResolver = new UserDataValueResolver($this->security);
  }

  /**
   * @test
   */
  public function givenNullArgumentType_whenResolve_shouldReturnEmptyArray(): void {
    // given
    $request = new Request();
    $argument = new ArgumentMetadata('name', null, false, false, null);

    // when
    $result = $this->userDataValueResolver->resolve($request, $argument);

    // then
    Assert::assertEmpty($result);
  }

  /**
   * @test
   */
  public function givenNonUserDataArgumentType_whenResolve_shouldReturnEmptyArray(): void {
    // given
    $request = new Request();
    $argument = new ArgumentMetadata('name', User::class, false, false, null);

    // when
    $result = $this->userDataValueResolver->resolve($request, $argument);

    // then
    Assert::assertEmpty($result);
  }

  /**
   * @test
   */
  public function givenNullUser_whenResolve_shouldReturnEmptyArray(): void {
    // given
    $this->expectException(AccessDeniedException::class);
    $this->expectExceptionMessage('Access Denied.');

    $request = new Request();
    $argument = new ArgumentMetadata('name', UserData::class, false, false, null);

    $this->security
        ->expects(static::once())
        ->method('getUser')
        ->willReturn(null);

    // when
    $this->userDataValueResolver->resolve($request, $argument);

    // then
  }

  /**
   * @test
   */
  public function givenValid_whenResolve_shouldReturnUserArray(): void {
    // given
    $request = new Request();
    $argument = new ArgumentMetadata('name', UserData::class, false, false, null);

    $userData =
        (new UserData())
            ->setUserIdentifier('username')
            ->setPassword('password')
            ->setCompanyId(123);

    $this->security
        ->expects(static::once())
        ->method('getUser')
        ->willReturn($userData);

    // when
    $result = $this->userDataValueResolver->resolve($request, $argument);

    // then
    Assert::assertNotEmpty($result);
    Assert::assertContains($userData, $result);
  }
}