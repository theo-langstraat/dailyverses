<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void
{

    $services = $container->services();

    // Default settings for all services in this extension
    $services->defaults()
        ->autowire()
        ->autoconfigure()
        ->public(true);

    // Autoload all your classes except the Domain/Model folder
    $services->load(
        'Theolangstraat\\Dailyverses\\',
        __DIR__ . '/../Classes/*'
        )
        ->exclude(__DIR__ . '/../Classes/Domain/Model/*');

};


