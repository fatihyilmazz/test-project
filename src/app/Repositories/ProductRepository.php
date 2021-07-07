<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends BaseRepository
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        parent::__construct($product);

        $this->product = $product;
    }

    /**
     * @param int $productId
     *
     * @return Product|Model|null
     */
    public function getProductById(int $productId)
    {
        return $this->product::with([
            'categories:category_id,is_main',
            'images',
        ])
            ->find($productId);
    }
}
