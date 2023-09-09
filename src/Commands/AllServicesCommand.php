<?php

namespace Elsayed85\LmsRedis\Commands;

use Illuminate\Console\Command;

class AllServicesCommand extends Command
{
    public $signature = 'lms-redis:services:all';

    public $description = 'List all services';

    public function handle(): void
    {

    }
}
