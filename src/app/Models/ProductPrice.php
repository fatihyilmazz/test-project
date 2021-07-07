<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_prices';

    protected $fillable = [
        'price',
        'valid_from',
        'valid_to',
    ];

    protected $casts = [
        'id'         => 'int',
        'product_id' => 'int',
        'price'      => 'float',
        'valid_from' => 'datetime',
        'valid_to'   => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $visible = [
        'price',
        'valid_from',
        'valid_to',
    ];
}
