<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

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
        'active' => 'nullable'
    ];
}
