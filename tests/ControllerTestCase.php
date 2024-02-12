<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

abstract class ControllerTestCase extends WebTestCase {

  private static KernelBrowser $browser;
  private static Container $container;


  public function setUp(): void {
    self::$browser = self::createClient();
    self::$container = self::getContainer();
  }

  public static function getBrowser(): KernelBrowser {
    return self::$browser;
  }

  public static function getService(string $id): mixed {
    return self::$container->get($id);
  }
}