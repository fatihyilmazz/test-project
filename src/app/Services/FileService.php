<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileService extends BaseService
{
    public function __construct(CacheService $cacheService)
    {
        parent::__construct($cacheService);
    }

    /**
     * @param string $disk
     * @param string $path
     *
     * @return string|null
     */
    public function get(string $disk, string $path)
    {
        try {
            return Storage::disk($disk)->get($path);
        } catch (\Exception $e) {
            //..
        }

        return null;
    }

    /**
     * @param string $disk
     * @param string $path
     * @param $content
     * @param array $options
     *
     * @return bool|null
     */
    public function set(string $disk, string $path, $content, $options = [])
    {
        try {
            return Storage::disk($disk)->put($path, $content, $options);
        } catch (\Exception $e) {
            //...
        }

        return null;
    }
}
