<?php

declare(strict_types = 1);

use App\Security\Service\UserDataProviderService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Config\SecurityConfig;

return static function(
    SecurityConfig $securityConfig,
    ContainerConfigurator $containerConfigurator): void {
  $securityConfig
      ->passwordHasher(PasswordAuthenticatedUserInterface::class)
      ->algorithm('bcrypt');

  $securityConfig
      ->provider('user_data_provider')
      ->id(UserDataProviderService::class);

  $securityConfig
      ->firewall('dev')
      ->pattern('^/(_(profiler|wdt)|css|images|js)/')
      ->security(false);

  $securityConfig
      ->firewall('main')
      ->lazy(true)
      ->provider('user_data_provider');

  $securityConfig
      ->firewall('main')
      ->formLogin()
      ->loginPath('security_login')
      ->checkPath('security_login')
      ->useReferer(true)
      ->alwaysUseDefaultTargetPath(false);

  $securityConfig
      ->firewall('main')
      ->logout()
      ->path('security_logout');

  $securityConfig
      ->firewall('main')
      ->rememberMe()
      ->secret('%kernel.secret%')
      ->lifetime(604800);

  if ($containerConfigurator->env() === 'test') {
    $securityConfig
        ->passwordHasher(PasswordAuthenticatedUserInterface::class)
        ->algorithm('auto')
        ->cost(4)
        ->timeCost(3)
        ->memoryCost(10);
  }
};
