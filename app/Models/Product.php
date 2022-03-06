<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

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
        'category' => 'required|exists:categories,id'
    ];

    // relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
