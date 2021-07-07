<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class AuthService extends BaseService
{
    const ID_PASSWORD_GRANT_CLIENT = 2;

    public function __construct(CacheService $cacheService)
    {
        parent::__construct($cacheService);
    }

    public function getOAuthClient(string $disk, string $path)
    {
        try {
            return Storage::disk($disk)->get($path);
        } catch (\Exception $e) {
            //..
        }

        return null;
    }
}
