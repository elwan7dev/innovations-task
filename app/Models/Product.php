<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'price',
        'active',
        'description',
        'category_id'
    ];

    protected $casts = [
        'name' => 'string',
        'price' => 'float',
        'active' => 'boolean',
        'description' => 'string',
    ];

    public static $rules = [
        'name' => 'required|unique:products,name',
        'price' => 'required|min:1|numeric',
        'active' => 'nullable',
        'description' => 'nullable',
        'category' => 'required|exists:categories,id',
        'images' => 'image|max:2048|mimes:jpg,png,jpeg,gif,svg'
    ];

    // relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
