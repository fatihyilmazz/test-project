<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    /**
     * @var Category
     */
    protected $category;

    /**
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        parent::__construct($category);

        $this->category = $category;
    }
}
