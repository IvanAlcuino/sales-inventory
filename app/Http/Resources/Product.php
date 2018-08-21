<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;  
use App\Inventory;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name, 
            'price' => $this->price,  
            'image_url' => $this->image_url,   
            'current_stock' => $this->current_stock, 
        ];
    }

    public function with($request){
        return [
            'version' => '1.0.0',
            'author_url' => url('...')
        ];
    }
}
