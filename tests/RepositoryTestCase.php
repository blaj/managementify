<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;

abstract class RepositoryTestCase extends KernelTestCase {

  private static Container $container;

  public function setUp(): void {
    self::bootKernel();
    self::$container = self::getContainer();
  }

  public static function getService(string $id): mixed {
    return self::$container->get($id);
  }
}