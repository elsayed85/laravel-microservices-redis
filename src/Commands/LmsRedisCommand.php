<?php

namespace Elsayed85\LmsRedis\Commands;

use Illuminate\Console\Command;

class LmsRedisCommand extends Command
{
    public $signature = 'lms-redis';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
