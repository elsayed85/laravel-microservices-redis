<?php

namespace Elsayed85\LmsRedis;

use Elsayed85\LmsRedis\Commands\LmsRedisInstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Elsayed85\LmsRedis\Commands\LmsRedisCommand;

class LmsRedisServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('lms-redis')
            ->hasConfigFile()
            ->hasCommands([
                LmsRedisCommand::class,
                LmsRedisInstallCommand::class
            ]);
    }
}
