<?php

namespace Elsayed85\LmsRedis\Services\RatingService;

use Elsayed85\LmsRedis\LmsRedis;

class RatingRedisService extends LmsRedis
{
    public function getServiceName(): string
    {
        return 'rating';
    }
}
