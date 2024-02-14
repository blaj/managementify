<?php

declare(strict_types = 1);

use App\Common\Doctrine\Function\CastFunction;
use App\Common\Doctrine\Type\BigIntType;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function(ContainerConfigurator $containerConfigurator): void {
  $containerConfigurator->extension('doctrine', [
      'dbal' => [
          'connections' => [
              'default' => [
                  'url' => '%env(resolve:DATABASE_URL)%',
                  'profiling_collect_backtrace' => '%kernel.debug%',
                  'driver' => 'pdo_pgsql',
                  'server_version' => '16',
                  'charset' => 'UTF8',
                  'use_savepoints' => true,
              ],
              'migrations' => [
                  'url' => '%env(resolve:DATABASE_MIGRATIONS_URL)%',
                  'profiling_collect_backtrace' => '%kernel.debug%',
                  'driver' => 'pdo_pgsql',
                  'server_version' => '16',
                  'charset' => 'UTF8',
                  'use_savepoints' => true,
              ],
          ],
          'types' => [
              'bigint' => BigIntType::class
          ],
      ],
      'orm' => [
          'auto_generate_proxy_classes' => true,
          'enable_lazy_ghost_objects' => true,
          'report_fields_where_declared' => true,
          'validate_xml_mapping' => true,
          'naming_strategy' => 'doctrine.orm.naming_strategy.underscore_number_aware',
          'auto_mapping' => true,
          'mappings' => [
              'User' => [
                  'type' => 'attribute',
                  'is_bundle' => false,
                  'dir' => '%kernel.project_dir%/src/User/Entity',
                  'prefix' => 'App\User\Entity',
                  'alias' => 'User',
              ],
              'Specialist' => [
                  'type' => 'attribute',
                  'is_bundle' => false,
                  'dir' => '%kernel.project_dir%/src/Specialist/Entity',
                  'prefix' => 'App\Specialist\Entity',
                  'alias' => 'Specialist',
              ],
              'Client' => [
                  'type' => 'attribute',
                  'is_bundle' => false,
                  'dir' => '%kernel.project_dir%/src/Client/Entity',
                  'prefix' => 'App\Client\Entity',
                  'alias' => 'Client',
              ],
              'Visit' => [
                  'type' => 'attribute',
                  'is_bundle' => false,
                  'dir' => '%kernel.project_dir%/src/Visit/Entity',
                  'prefix' => 'App\Visit\Entity',
                  'alias' => 'Visit',
              ],
          ],
          'dql' => [
              'string_functions' => [
                  'CAST' => CastFunction::class
              ]
          ]
      ],
  ]);
  if ($containerConfigurator->env() === 'test') {
    $containerConfigurator->extension('doctrine', [
        'dbal' => [
            'dbname_suffix' => '_test%env(default::TEST_TOKEN)%',
        ],
    ]);
  }
  if ($containerConfigurator->env() === 'prod') {
    $containerConfigurator->extension('doctrine', [
        'orm' => [
            'auto_generate_proxy_classes' => false,
            'proxy_dir' => '%kernel.build_dir%/doctrine/orm/Proxies',
            'query_cache_driver' => [
                'type' => 'pool',
                'pool' => 'doctrine.system_cache_pool',
            ],
            'result_cache_driver' => [
                'type' => 'pool',
                'pool' => 'doctrine.result_cache_pool',
            ],
        ],
    ]);
    $containerConfigurator->extension('framework', [
        'cache' => [
            'pools' => [
                'doctrine.result_cache_pool' => [
                    'adapter' => 'cache.app',
                ],
                'doctrine.system_cache_pool' => [
                    'adapter' => 'cache.system',
                ],
            ],
        ],
    ]);
  }
};
