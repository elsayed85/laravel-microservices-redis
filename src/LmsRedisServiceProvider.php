<?php

namespace Elsayed85\LmsRedis;

use Elsayed85\LmsRedis\Commands\LmsRedisInstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LmsRedisServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('lms-redis')
            ->hasConfigFile()
            ->hasCommands([
                LmsRedisInstallCommand::class
            ]);
    }

    public function boot()
    {
        parent::boot();

        $this->publishes([
            __DIR__.'/Commands/LmsRedisConsumeCommand.php' => app_path('Console/Commands/LmsRedisConsumeCommand.php'),
        ], 'lms-redis-consume-command');


    }
}
