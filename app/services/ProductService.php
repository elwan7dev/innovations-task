<?php

namespace App\services;


use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductService
{


    public function getProductsWithConditions(Request $request)
    {
        $query = Product::query();
        $perPage = ($request->has('perPage') && $request->perPage) ? $request->perPage : 10;

        if ($request->has('lessThan') &&  is_numeric($request->lessThan)){
            // less than
            $query = $query->where('price','<',$request->lessThan ?? 100);
        }elseif ($request->has('greaterThan') &&  is_numeric($request->greaterThan)){
            // greater than
            $query = $query->where('price','>',$request->lessThan ?? 100);
        }

        if ($request->has('type') && !empty($request->type) && $request->type == 'active')
            $products = $query->where('active', true)->paginate($perPage);
        elseif ($request->has('type') && !empty($request->type) && $request->type == 'inActive')
            $products = $query->where('active', false)->paginate($perPage);
        else
            $products = $query->paginate($perPage);

        return $products;
    }
}
