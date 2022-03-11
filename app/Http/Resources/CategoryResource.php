<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $returned = [
            'name' => $this->name,
            'active' => (bool)$this->active,
        ];

        if ($request->has('productsActive') && in_array($request->productsActive, ['true', 'false'])) {
            // convert string to boolean keyword
            $active = filter_var($request->productsActive, FILTER_VALIDATE_BOOLEAN);
            if ($active){
                $returned['products_count'] =$this->active_products_count;
                $returned['products'] = ProductResource::collection($this->activeProducts);
            }
            else{
                $returned['products_count'] =$this->in_active_products_count;
                $returned['products'] = ProductResource::collection($this->inActiveProducts);
            }
        }else{
            $returned['products_count'] =$this->products_count;
            $returned['products'] = ProductResource::collection($this->products);
        }
        return  $returned;
    }
}
