<?php

namespace App\services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryService
{


    public function getCategoriesWithConditions(Request $request)
    {
        $query = Category::query();
        $perPage = ($request->has('perPage') && $request->perPage) ? $request->perPage : 10;

        if ($request->has('productsActive') && in_array($request->productsActive, ['true', 'false'])){
            // convert string to boolean keyword
            $value = filter_var($request->productsActive, FILTER_VALIDATE_BOOLEAN);
            $query->whereHas('products', function (Builder $builder) use ($value) {
                $builder->where('active',$value);
            });
        }

        if ($request->has('productsLessThan') && is_numeric($request->productsLessThan)){
            $query->whereHas('products', function (Builder $builder) use ($request){
                $builder->where('price','<',$request->productsLessThan ?? 100);
            });
        }

        if ($request->has('productsGreaterThan') && is_numeric($request->productsGreaterThan)){
            $query->whereHas('products', function (Builder $builder) use ($request){
                $builder->where('price','>',$request->productsGreaterThan ?? 100);
            });
        }
        // DoesntHave products
        if ($request->has('withoutProducts')){
            $query->whereDoesntHave('products');
        }
        // eager loading
        if ($request->has('productsActive') && in_array($request->productsActive, ['true', 'false'])) {
            // convert string to boolean keyword
            $active = filter_var($request->productsActive, FILTER_VALIDATE_BOOLEAN);
            if ($active)
                $query->with('activeProducts')->withCount('activeProducts');
            else
                $query->with('inActiveProducts')->withCount('inActiveProducts');
        }else{
            $query->with('products')->withCount('products');
        }

        if ($request->has('type') && !empty($request->type) && $request->type == 'active')
            $categories = $query->where('active', true)->paginate($perPage);
        elseif ($request->has('type') && !empty($request->type) && $request->type == 'inActive')
            $categories = $query->where('active', false)->paginate($perPage);
        else
            $categories = $query->paginate($perPage);

        return $categories;

    }
}
