<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('framework', [
        'default_locale' => 'pl',
        'translator' => [
            'default_path' => '%kernel.project_dir%/translations',
            'fallbacks' => [
                'pl',
            ],
            'providers' => null,
        ],
    ]);
};
