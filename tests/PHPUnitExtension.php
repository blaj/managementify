<?php

namespace App\Tests;

use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\BeforeFirstTestHook;

class PHPUnitExtension implements BeforeFirstTestHook, AfterLastTestHook {

  public function executeBeforeFirstTest(): void {
    passthru(
        sprintf(
            'APP_ENV=%s php "%s/../bin/console" --env=test doctrine:database:create',
            $_ENV['APP_ENV'],
            __DIR__
        ));
    passthru(
        sprintf(
            'APP_ENV=%s php "%s/../bin/console" --env=test doctrine:schema:create',
            $_ENV['APP_ENV'],
            __DIR__
        ));
  }

  public function executeAfterLastTest(): void {
    passthru(
        sprintf(
            'APP_ENV=%s php "%s/../bin/console" --env=test --force doctrine:database:drop',
            $_ENV['APP_ENV'],
            __DIR__
        ));
  }
}