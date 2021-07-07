<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_categories';

    protected $fillable = [
        'category_id',
        'is_main',
    ];

    protected $casts = [
        'id'            => 'int',
        'product_id'    => 'int',
        'category_id'   => 'int',
        'is_main'       => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
