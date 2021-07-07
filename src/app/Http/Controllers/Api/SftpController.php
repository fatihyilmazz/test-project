<?php

namespace App\Http\Controllers\Api;

use App\Services\FileService;
use App\Services\ProductService;
use App\Http\Controllers\Controller;

class SftpController extends Controller
{
    /**
     * @var FileService
     */
    protected $fileService;

    /**
     * @var ProductService
     */
    protected $productService;

    public function __construct(FileService $fileService, ProductService $productService)
    {
        $this->fileService = $fileService;
        $this->productService = $productService;
    }

    public function test()
    {
        return $this->fileService->get('sftp', 'testfile.txt');
    }
}
