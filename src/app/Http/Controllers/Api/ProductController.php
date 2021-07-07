<?php

namespace App\Http\Controllers\Api;

use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductRequest;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getProducts();

        return response($products);
    }

    /**
     * @param ProductRequest $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $request->validated();

        $product = $this->productService->saveProduct($request->all());

        if ($product) {
            return response([
                'message' => 'Product created successfully.',
            ]);
        }

        return response([
            'message' => 'An error occurred',
        ],500);
    }

    /**
     * @param $productIdentifier
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getProductByIdentifier($productIdentifier)
    {
        $product = $this->productService->getProductByIdentifier($productIdentifier);

        if ($product !== null) {
            return response($product);
        }

        return response([
            'message' => 'Not found.',
        ], 404);
    }
}
