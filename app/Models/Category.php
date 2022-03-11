<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
      'name',
      'active'
    ];

    protected $casts = [
        'name' => 'string',
        'active' => 'boolean',

    ];

    public static $rules = [
        'name' => 'required|unique:categories,name',
        'active' => 'nullable',
        'image' => 'image|max:2048|mimes:jpg,png,jpeg,gif,svg'
    ];

    // relations
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts()
    {
        return $this->products()->where('active',true);
    }
    public function inActiveProducts()
    {
        return $this->products()->where('active',false);
    }
}
