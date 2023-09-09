<?php

namespace Elsayed85\LmsRedis;

use Elsayed85\LmsRedis\Commands\AllServicesCommand;
use Elsayed85\LmsRedis\Commands\LmsRedisInstallCommand;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;
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
                LmsRedisInstallCommand::class,
                AllServicesCommand::class,
            ]);
    }

    public function register()
    {
        parent::register();

        $this->app->singleton('lms-redis', function ($app) {
            $config = $app->make('config')->get('lms-redis.redis', []);
            return new RedisManager($app, Arr::pull($config, 'client', 'phpredis'), $config);
        });

        $this->app->bind('lms-redis.connection', function ($app) {
            return $app['lms-redis']->connection();
        });
    }

    public function boot()
    {
        parent::boot();

        $this->publishes([
            __DIR__.'/Commands/LmsRedisConsumeCommand.php' => app_path('Console/Commands/LmsRedisConsumeCommand.php'),
        ], 'lms-redis-consume-command');
    }
}
