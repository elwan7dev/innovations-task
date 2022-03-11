<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithValidation, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;


    /**
    * @param array $row
    *
    * @return Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'name' => $row['name'],
            'price'  => $row['price'],
            'active'  => $row['active'],
            'description'  => $row['description'],
            'category_id'  =>  Category::query()->where('name', $row['category_name'])->first()->id
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'category_name' => Rule::exists('categories', 'name'),
            'active' => ['required', 'boolean'],
            'price' => ['required', 'numeric', 'min:1'],
        ];
    }


}
