<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_images';

    protected $fillable = [
        'image',
    ];

    protected $casts = [
        'id'         => 'int',
        'product_id' => 'int',
        'image'      => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $visible = [
        'image'
    ];

    public function setImageAttribute($value)
    {
        $this->attributes['image'] = strtolower(trim($value));
    }
}
