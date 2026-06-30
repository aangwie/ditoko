<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'cover_image',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
        ];
    }

    /**
     * Auto-generate slug from name if not provided.
     */
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
