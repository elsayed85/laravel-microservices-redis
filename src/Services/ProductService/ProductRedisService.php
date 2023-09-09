<?php

namespace Elsayed85\LmsRedis\Services\ProductService;

use Elsayed85\LmsRedis\LmsRedis;

class ProductRedisService extends LmsRedis
{
    public function getServiceName(): string
    {
        return 'product';
    }
}
