<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    const ID_IS_ACTIVE = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    protected $fillable = [
        'parent_id',
        'name',
        'order',
        'is_active',
    ];

    protected $casts = [
        'id'         => 'int',
        'parent_id'  => 'int',
        'name'       => 'string',
        'order'      => 'int',
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }
}
