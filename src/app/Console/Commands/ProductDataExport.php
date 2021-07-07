<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Services\ProductService;
use GuzzleHttp\Exception\GuzzleException;

class ProductDataExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:export-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export product data every 4 hours';

    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        parent::__construct();

        $this->productService = $productService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     *
     * @throws GuzzleException
     */
    public function handle()
    {
        // I sent request with different ways. One of them is with 3rd party package called Guzzle and the other one is with curl.

        $products = $this->productService->getProducts();
        $authRequestUrl = sprintf('%s/%s', env('SERVER_URL'), 'api/login');
        $client = new Client();
        $response = $client->request('POST', $authRequestUrl, [
            'headers' => ['Content-type: application/x-www-form-urlencoded'],
            'form_params' => [
                'email' => 'fatih@test.com',
                'password' => '123456',
            ]
        ]);

        $data = json_decode($response->getBody());
        $token = $data->token;

         try {
             foreach ($products as $product) {
                 $productData = $this->productService->formatProductResponse($product);

                $this->callRequest('post', env('SERVER_URL'), '/api/store-product', [
                     'Content-type:application/x-www-form-urlencoded',
                     'Authorization:Bearer ' . $token,
                     'Accept:application/json',
                     'Content-Length:' . strlen(http_build_query($data)),
                ], [
                     'name' => $productData['name'],
                     'identifier' => $productData['identifier'],
                     'description' => $productData['description'],
                     'categories' => implode(',', $productData['categories']),
                     'prices' => implode(',', $this->mergePrices($productData)),
                     'images' => implode(',', $productData['images']),
                 ]);
             }
         } catch (\Exception $e) {
             //.. logs here
         }

         $this->info('Products sent to API.');
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function mergePrices(array $data)
    {
        $mergedData = [];
        foreach ($data['prices'] as $price) {
            $mergedData[] = sprintf('%s|%s|%s', $price['price'], $price['valid_from'], $price['valid_to']);
        }

        return $mergedData;
    }

    /**
     * @param string $method
     * @param string $url
     * @param string $uri
     * @param array $headers
     * @param array $data
     *
     * @return bool|string
     *
     * @throws \Exception
     */
    public function callRequest(string $method, string $url, string $uri, array $headers = [], $data)
    {
        $requestUrl = sprintf('%s%s', $url, $uri);

        $curl = curl_init();
        switch ($method) {
            case 'post':
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($curl, CURLOPT_TIMEOUT, 300);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(json_encode($data), '', '&'));
                break;
            case 'get':
                if (!empty($data)) {
                    $requestUrl = sprintf("%s?%s", $requestUrl, http_build_query($data));
                }
                break;
            default:
                throw new \Exception('Unsupported method: ' . $method);
        }

        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        return $data;
    }
}
