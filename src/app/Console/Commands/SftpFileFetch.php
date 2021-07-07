<?php

namespace App\Console\Commands;

use App\Services\FileService;
use Illuminate\Console\Command;
use App\Services\ProductService;
use Illuminate\Support\Facades\Storage;

class SftpFileFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:sftp-fetch-product-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch file which including product data every 2 hours';

    /**
     * @var FileService
     */
    protected $fileService;

    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * @param FileService $fileService
     * @param ProductService $productService
     */
    public function __construct(FileService $fileService, ProductService $productService)
    {
        parent::__construct();
        $this->fileService = $fileService;
        $this->productService = $productService;
    }

    /**r
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $productFile = 'sfpt-input-test-data.csv';

            $sftpFile = $this->fileService->get('sftp', $productFile);
            if (empty($sftpFile)) {
                $this->info(sprintf('Sftp connection/sftp file error.'));
                return;
            }

            Storage::disk('public')->put($productFile, $sftpFile);

            $file = storage_path('app/public/' . $productFile);
            $productsArray = $this->csvToArray($file);

            foreach ($productsArray as $key => $product) {
                $this->productService->saveProduct($product);
            }

            $this->info(sprintf('Product(s) saved/updated from csv file'));
        } catch (\Exception $e) {
            dd($e);
        }

    }

    function csvToArray($filename = '', $delimiter = ';')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}
