<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Http\Requests\Api\ProductRequest;

class ProductService extends BaseService
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @param CacheService $cacheService
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CacheService $cacheService, ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        parent::__construct($cacheService);

        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getProducts()
    {
        try {
            return $this->productRepository->findByAttributes([], self::FETCH_TYPE_PAGINATE, ['images', 'prices']);
        } catch (\Exception $e) {
            // Maybe logs here..
        }

        return null;
    }

    public function getProductByIdentifier($identifier)
    {
        $cacheKey = sprintf('%s_%s', Product::CACHE_KEY_PRODUCT_IDENTIFIER, $identifier);
        $product = $this->cacheService->get($cacheKey);

        if (!empty($product)) {
            return $product;
        }

        /** @var Product $product */
        $product = $this->productRepository->findByAttributes(['identifier' => $identifier],null, ['images', 'prices']);

        if ($product instanceof Product) {
            $product = $this->formatProductResponse($product);

            $this->cacheService->set($cacheKey, $product);

            return $product;
        }

        return null;
    }

    /**
     * @param Product $product
     *
     * @return array|null
     */
    public function formatProductResponse(Product $product): ?array
    {
        try {
            return [
                'name' => $product->name,
                'identifier' => $product->identifier,
                'description' => $product->description,
                'categories' => $product->categories()->pluck('name')->toArray(),
                'prices' => $product->prices()->select('price', 'valid_from', 'valid_to')->get(),
                'images' => $product->images->pluck('image')->toArray(),
            ];
        } catch (\Exception $e) {
            //..
        }

        return null;
    }

    /**
     * @param array $productArray
     *
     * @return bool
     */
    public function saveProduct(array $productArray): bool
    {
        try {
            $formattedData = $this->formatStoreProductData($productArray);
            DB::beginTransaction();

            $categoryIds = $this->createOrUpdateCategories($formattedData);

            $product = $this->productRepository->updateOrCreate([
                'name' => $formattedData['name'],
                'identifier' => $formattedData['identifier'],
                'description' => $formattedData['description'],
            ]);

            $product->categories()->sync($categoryIds, true);

            foreach ($formattedData['prices'] as $price) {
                $product->prices()->updateOrCreate(
                    $price,
                );
            }

            foreach ($formattedData['images'] as $image) {
                $product->images()->updateOrCreate($image);
            }

            DB::commit();

            $this->cacheService->delete(sprintf('%s_%s', Product::CACHE_KEY_PRODUCT_IDENTIFIER, $product->identifier));

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Maybe logs here..
        }

        return false;
    }

    /**
     * @param array $productArray
     *
     * @return array
     */
    public function createOrUpdateCategories(array $productArray)
    {
        $categoryIds = [];
        foreach ($productArray['categories'] as $productCategory) {
            $category = $this->categoryRepository->findByAttributes(['name' => trim($productCategory['name'])]);

            if (!($category instanceof Category)) {
                $category = $this->categoryRepository->create([
                    'name' => $productCategory['name'],
                    'is_active' => Category::ID_IS_ACTIVE,
                ]);
            }

            $categoryIds[] = $category->id;
        }

        return $categoryIds;
    }

    /**
     * @param array $productArray
     *
     * @return array|null
     */
    public function formatStoreProductData(array $productArray)
    {
        if (!empty($productArray['prices'])) {
            $pricesArray = [];
            $prices = explode(',', $productArray['prices']);
            foreach ($prices as $price) {
                $price = explode('|', $price);
                if (count($price) < 3) {
                    return null;
                }

                $pricesArray[] = [
                    'price' => $price[0],
                    'valid_from' => $price[1],
                    'valid_to' => $price[2],
                ];
            }

            $productArray['prices'] =  $pricesArray;
        }

        if (!empty($productArray['images'])) {
            $images = explode(',', $productArray['images']);
            $formatForCreate = [];
            foreach ($images as $image) {
                $formatForCreate[]['image'] = $image;
            }

            $productArray['images'] = $formatForCreate;
        }

        if (!empty($productArray['categories'])) {
            $categories = explode(',', $productArray['categories']);
            $formatForCreate = [];
            foreach ($categories as $category) {
                $formatForCreate[]['name'] = $category;
            }
            $productArray['categories'] =  $formatForCreate;
        }

        return $productArray;
    }
}
