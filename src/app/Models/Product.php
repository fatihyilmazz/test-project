<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    const CACHE_KEY_PRODUCT_IDENTIFIER = 'product_identifier';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    protected $fillable = [
        'name',
        'identifier',
        'description',
    ];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'id'          => 'int',
        'name'        => 'string',
        'identifier'  => 'string',
        'description' => 'string',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->as('product_categories')
            ->withPivot('is_main')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function mainCategory()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->as('main_categories')
            ->wherePivot('is_main', '=', true);
    }

    /**
     * @return HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * @return HasMany
     */
    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function getCategories()
    {
        return $this->categories()->pluck('name');
    }
}
