<?php

namespace App\Services;


class BaseService
{
    const FETCH_TYPE_PAGINATE = 1;

    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }
}
